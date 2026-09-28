<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    protected $fillable = [
        'asset_id',

        'borrower_name',
        'borrower_position',
        'borrower_department',
        'borrower_phone',
        'borrower_signature',

        'borrowed_at',
        'expected_return_at',
        'returned_at',

        'condition_before',
        'condition_after',

        'purpose',
        'notes',

        'approved_by',

        'document_number',
    ];

    protected $casts = [
        'borrowed_at'        => 'datetime',
        'expected_return_at' => 'datetime',
        'returned_at'        => 'datetime',
    ];

    /**
     * Asset yang dipinjam
     */
    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    /**
     * Request peminjaman yang menghasilkan loan ini
     */
    public function loanRequest()
    {
        return $this->hasOne(LoanRequest::class);
    }

    /**
     * Cek apakah asset sudah dikembalikan
     */
    public function isReturned(): bool
    {
        return !is_null($this->returned_at);
    }

    /**
     * Cek apakah peminjaman sudah terlambat
     */
    public function isOverdue(): bool
    {
        return !$this->isReturned()
            && $this->expected_return_at
            && $this->expected_return_at->isPast();
    }
}
