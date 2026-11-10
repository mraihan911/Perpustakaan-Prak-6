<?php

namespace Modules\Circulation\Listeners;

use Modules\Circulation\Events\BookBorrowed;
use Modules\Circulation\Models\LibraryActivityLog;

class RecordBookBorrowed
{
    public function handle(BookBorrowed $event): void
    {
        LibraryActivityLog::create([
            'event' => 'BookBorrowed',
            'payload' => [
                'borrowing_id' => $event->borrowing->id,
                'code' => $event->borrowing->borrowing_code,
                'member_id' => $event->borrowing->member_id,
                'detail_count' => $event->borrowing->details->count(),
            ],
        ]);
    }
}