<?php

namespace Modules\Circulation\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Borrowing extends Model
{
    use SoftDeletes;

    protected $table = 'library_borrowings';

    protected $fillable = [
        'borrowing_code',
        'member_id',
        'borrowed_at',
        'due_at',
        'returned_at',
        'status',
        'fine_amount',
    ];

    protected $casts = [
        'borrowed_at' => 'date',
        'due_at' => 'date',
        'returned_at' => 'date',
        'fine_amount' => 'integer',
    ];

    public function member(): BelongsTo
    {
        return $this->belongsTo(\Modules\Membership\Models\Member::class, 'member_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(BorrowingDetail::class, 'borrowing_id');
    }
}