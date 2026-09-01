<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Borrowing extends Model
{
    use HasFactory;

    protected $fillable = [
        'member_id',
        'book_id',
        'borrow_date',
        'due_date',
        'return_date',
        'status',
        'fine_amount',
    ];

    protected $casts = [
        'borrow_date'  => 'date',
        'due_date'     => 'date',
        'return_date'  => 'date',
        'fine_amount'  => 'decimal:2',
    ];

    /**
     * The member who borrowed the book.
     */
    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    /**
     * The book being borrowed.
     */
    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    /**
     * Whether this borrowing is currently overdue and unreturned.
     */
    public function getIsOverdueAttribute(): bool
    {
        return is_null($this->return_date) && $this->due_date->isPast();
    }
}
