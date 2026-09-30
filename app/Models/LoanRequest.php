<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoanRequest extends Model
{
    protected $fillable = [
        'request_number',
        'asset_id',
        'borrower_name',
        'borrower_position',
        'borrower_department',
        'borrower_phone',
        'borrower_signature',
        'purpose',
        'notes',
        'status',
        'reject_reason',
        'loan_id',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function loan()
    {
        return $this->belongsTo(Loan::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            'pending'  => 'Menunggu',
            'approved' => 'Disetujui',
            'rejected' => 'Ditolak',
        ][$this->status] ?? $this->status;
    }

    public function getStatusBadgeAttribute(): string
    {
        return [
            'pending'  => 'warning',
            'approved' => 'success',
            'rejected' => 'danger',
        ][$this->status] ?? 'secondary';
    }
}
