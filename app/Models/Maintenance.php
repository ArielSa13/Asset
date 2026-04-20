<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $fillable = [
        'asset_id',
        'type',
        'description',
        'vendor_name',
        'vendor_phone',
        'cost',
        'started_at',
        'completed_at',
        'notes',
        'status',
    ];

    protected $casts = [
        'cost'         => 'decimal:2',
        'started_at'   => 'date',
        'completed_at' => 'date',
    ];

    const TYPE_PREVENTIVE = 'preventive';
    const TYPE_CORRECTIVE = 'corrective';
    const TYPE_INSPECTION = 'inspection';

    const STATUS_PENDING    = 'pending';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_COMPLETED  = 'completed';

    public function getTypeLabelAttribute(): string
    {
        return self::types()[$this->type] ?? ucfirst($this->type);
    }

    public static function types(): array
    {
        return [
            self::TYPE_PREVENTIVE => 'Preventif (Rutin)',
            self::TYPE_CORRECTIVE => 'Korektif (Perbaikan)',
            self::TYPE_INSPECTION => 'Inspeksi',
        ];
    }

    public static function statuses(): array
    {
        return [
            self::STATUS_PENDING     => 'Pending',
            self::STATUS_IN_PROGRESS => 'In Progress',
            self::STATUS_COMPLETED   => 'Completed',
        ];
    }

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING     => 'warning',
            self::STATUS_IN_PROGRESS => 'primary',
            self::STATUS_COMPLETED   => 'success',
            default                  => 'secondary',
        };
    }
}
