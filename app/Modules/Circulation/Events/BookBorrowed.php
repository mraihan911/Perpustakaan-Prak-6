<?php

namespace Modules\Circulation\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Modules\Circulation\Models\Borrowing;

class BookBorrowed
{
    use Dispatchable, SerializesModels;

    public function __construct(public Borrowing $borrowing) {}
}