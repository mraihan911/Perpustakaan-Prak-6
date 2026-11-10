<?php
namespace Modules\Circulation\ValueObjects;

use InvalidArgumentException;

final readonly class Fine
{
    public function __construct(public int $lateDays, public int $ratePerDay = 1000)
    {
        if ($lateDays < 0 || $ratePerDay < 0) throw new InvalidArgumentException('Hari terlambat dan tarif denda tidak boleh negatif.');
    }
    public function amount(): int { return $this->lateDays * $this->ratePerDay; }
    public function __toString(): string { return 'Rp'.number_format($this->amount(), 0, ',', '.'); }
}
