# Sistem Perpustakaan Modular — Praktikum MP-BED-06

Implementasi studi kasus tugas pada modul MP-BED-06 dengan Laravel 13 dan PHP 8.3+.

## Persiapan

Pastikan PHP 8.3+ beserta ekstensi `pdo_sqlite`, `dom`, `mbstring`, `openssl`, `tokenizer`, `xml`, `ctype`, `fileinfo`, dan Composer tersedia. Untuk Windows/XAMPP, aktifkan ekstensi tersebut di `php.ini`.

Jika folder `.env` belum ada:

```powershell
Copy-Item .env.example .env
```

Lalu jalankan:

```bash
# Jika folder vendor/ belum tersedia, jalankan: composer install
php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

Buka `http://127.0.0.1:8000`. Database default menggunakan SQLite (`database/database.sqlite`). `migrate:fresh` menghapus tabel lama dan membuat ulang database; gunakan hanya pada database praktikum.

## Akun dan data contoh

Seeder membuat anggota contoh:

- Nomor anggota: `AGT-0001`, nama Budi Santoso, email `anggota@example.com`
- Nomor anggota: `AGT-0002`, nama Siti Aminah, email `anggota2@example.com`
- Buku: `9786021234567`, `9786021234568`, `9786021234569`

Data tersebut adalah data praktikum, bukan akun produksi.

## API untuk pengujian

Endpoint baca/peminjaman mengembalikan JSON. Endpoint DELETE dan restore dibatasi untuk user yang login dan memiliki role admin; gunakan akun admin hasil seeder `admin@example.com` / `password123` pada lingkungan lokal praktikum.

| Method | Endpoint | Fungsi |
|---|---|---|
| GET | `/api/library/dashboard` | Ringkasan katalog, anggota, peminjaman |
| GET | `/api/library/books` | Daftar buku + eager loading |
| GET | `/api/library/books?search=Laravel&available=1` | Scope pencarian dan buku tersedia |
| GET | `/api/library/books?category_id=2` | Filter kategori |
| GET | `/api/library/members` | Daftar anggota + role |
| POST | `/api/library/borrowings` | Buat peminjaman (JSON: `member_id`, `items`) |
| POST | `/api/library/borrowings/{id}/return` | Pengembalian (opsional `returned_at: YYYY-MM-DD`) |
| DELETE | `/api/library/books/{id}` | Soft delete buku |
| DELETE | `/api/library/members/{id}` | Soft delete anggota |
| DELETE | `/api/library/borrowings/{id}` | Soft delete peminjaman |
| POST | `/api/library/books/{id}/restore` | Restore buku terhapus lunak |
| POST | `/api/library/members/{id}/restore` | Restore anggota |
| POST | `/api/library/borrowings/{id}/restore` | Restore peminjaman |

Contoh request peminjaman:

```json
{
  "member_id": 1,
  "items": [{"book_id": 1, "quantity": 1}]
}
```

Contoh pengembalian terlambat:

```json
{"returned_at":"2026-11-20"}
```

Tarif denda contoh adalah Rp1.000 per hari keterlambatan. Batas peminjaman contoh adalah 5 transaksi aktif per anggota, dengan durasi 7 hari.

## Pengujian otomatis

```bash
php artisan test --compact tests/Feature/Modules/Library/LibraryDomainTest.php
```

Test mencakup validasi ISBN dan denda, relasi pivot/self-reference, eager loading, peminjaman berhasil, rollback karena stok kurang, pengembalian/denda, soft delete/restore, dan pencatatan domain event.

## Pengujian manual melalui Tinker

```bash
php artisan tinker
```

```php
// Eager loading dan jumlah query
DB::enableQueryLog();
$books = Modules\Catalog\Models\Book::with(['authors','publisher','categories'])->get();
count(DB::getQueryLog());

// Query scopes
Modules\Catalog\Models\Book::available()->search('Laravel')->get();
Modules\Catalog\Models\Book::available()->inCategory(2)->get();

// Soft delete dan restore
$book = Modules\Catalog\Models\Book::first();
$book->delete();
Modules\Catalog\Models\Book::count();
Modules\Catalog\Models\Book::withTrashed()->count();
$book->restore();

// Relasi pivot dan kategori bertingkat
$book = Modules\Catalog\Models\Book::with('categories')->first();
$book->categories->map(fn($category) => [$category->name, $category->pivot->added_at]);
$category = Modules\Catalog\Models\Category::whereNull('parent_id')->with('children')->first();
```

Untuk membuktikan transaction dan event, gunakan test otomatis. Test rollback memastikan tidak ada borrowing, stok tidak berubah, dan counter anggota tetap sama saat stok tidak cukup. Test event memastikan satu baris `BookBorrowed` tercatat pada `library_activity_logs`.

## Struktur modul tambahan

- `app/Modules/Catalog`: model buku/penulis/kategori/penerbit, ISBN, service katalog, migration.
- `app/Modules/Membership`: anggota/role, service status keanggotaan, migration.
- `app/Modules/Circulation`: peminjaman/detail, service transaction, event/listener, denda, API, migration.
- `app/Modules/Shared/Contracts`: kontrak lintas bounded context.

## Catatan

Endpoint API pada paket praktikum ini disediakan untuk pengujian lokal. Sebelum deployment, perkuat autentikasi, otorisasi, rate limiting, dan kebijakan audit sesuai kebutuhan produksi. Nilai durasi pinjam dan tarif denda merupakan aturan contoh yang dapat disesuaikan dengan ketentuan perpustakaan.
