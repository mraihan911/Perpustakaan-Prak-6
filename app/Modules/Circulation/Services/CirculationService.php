<?php

namespace Modules\Circulation\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Modules\Circulation\Models\Borrowing;
use Modules\Circulation\Models\BorrowingDetail;
use Modules\Circulation\Events\BookBorrowed;
use Modules\Circulation\ValueObjects\Denda;
use Modules\Shared\Contracts\BookCatalogInterface;
use Modules\Shared\Contracts\MemberAccessInterface;

class CirculationService
{
    public function __construct(
        private BookCatalogInterface $catalog,
        private MemberAccessInterface $members
    ) {}

    public function borrow(int $memberId, array $items): Borrowing
    {
        if (count($items) < 1) {
            throw ValidationException::withMessages(['items' => 'Minimal satu buku harus dipilih.']);
        }

        $borrowing = DB::transaction(function () use ($memberId, $items) {
            if (!$this->members->canBorrow($memberId)) {
                throw ValidationException::withMessages(['member' => 'Anggota tidak aktif/kedaluwarsa atau telah mencapai batas 5 peminjaman.']);
            }

            $b = Borrowing::create([
                'borrowing_code' => 'PJM-' . Str::upper((string) Str::ulid()),
                'member_id' => $memberId,
                'borrowed_at' => today(),
                'due_at' => today()->addDays(7),
                'status' => 'borrowed',
                'fine_amount' => 0,
            ]);

            foreach ($items as $item) {
                $qty = (int) ($item['quantity'] ?? 0);
                $book = $this->catalog->snapshot((int) $item['book_id']);

                if (!$book || $qty < 1 || !$this->catalog->reserve((int) $item['book_id'], $qty)) {
                    throw ValidationException::withMessages(['stock' => 'Buku tidak ditemukan atau stok tidak mencukupi: ' . ($book['title'] ?? $item['book_id'])]);
                }

                BorrowingDetail::create([
                    'borrowing_id' => $b->id,
                    'book_id' => $book['id'],
                    'book_title' => $book['title'],
                    'book_isbn' => $book['isbn'],
                    'quantity' => $qty,
                ]);
            }

            $this->members->incrementBorrowings($memberId);

            return $b->load('details', 'member');
        });

        event(new BookBorrowed($borrowing));

        return $borrowing;
    }

    public function returnBorrowing(Borrowing $borrowing, ?string $returnedAt = null): Borrowing
    {
        $returned = DB::transaction(function () use ($borrowing, $returnedAt) {
            $b = Borrowing::query()->lockForUpdate()->with('details')->findOrFail($borrowing->id);

            if ($b->status === 'returned') {
                throw ValidationException::withMessages(['borrowing' => 'Peminjaman ini sudah dikembalikan.']);
            }

            $date = $returnedAt ? \Illuminate\Support\Carbon::parse($returnedAt)->startOfDay() : today();
            $lateDays = max(0, (int) $b->due_at->diffInDays($date, false));
            $fine = new Denda($lateDays);

            foreach ($b->details as $d) {
                $this->catalog->release($d->book_id, $d->quantity);
            }

            $this->members->decrementBorrowings($b->member_id);

            $b->update([
                'returned_at' => $date,
                'status' => 'returned',
                'fine_amount' => $fine->hitung(),
            ]);

            return $b->fresh('details', 'member');
        });

        return $returned;
    }
}