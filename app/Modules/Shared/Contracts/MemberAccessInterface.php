<?php

namespace Modules\Shared\Contracts;

interface MemberAccessInterface
{
    public function canBorrow(int $memberId): bool;

    public function incrementBorrowings(int $memberId): void;

    public function decrementBorrowings(int $memberId): void;
}