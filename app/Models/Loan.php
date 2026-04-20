<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Loan extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'borrower_name',
        'borrower_department',
        'borrower_phone',
        'borrowed_at',
        'expected_return_at',
        'returned_at',
        'condition_before',
        'condition_after',
        'purpose',
        'notes',
        'approved_by',
    ];

    protected $casts = [
        'borrowed_at'         => 'datetime',
        'expected_return_at'  => 'datetime',
        'returned_at'         => 'datetime',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function isReturned(): bool
    {
        return ! is_null($this->returned_at);
    }

    public function isOverdue(): bool
    {
        return ! $this->isReturned()
            && $this->expected_return_at
            && $this->expected_return_at->isPast();
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->isReturned()) return 'Returned';
        if ($this->isOverdue())  return 'Overdue';
        return 'Active';
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->isReturned()) return 'success';
        if ($this->isOverdue())  return 'danger';
        return 'primary';
    }
}
