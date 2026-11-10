<?php

namespace Modules\Shared\Contracts;

interface BookCatalogInterface
{
    public function snapshot(int $bookId): ?array;

    public function reserve(int $bookId, int $quantity): bool;

    public function release(int $bookId, int $quantity): void;
}