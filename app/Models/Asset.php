<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'code',
        'category_id',
        'brand',
        'model',
        'serial_number',
        'purchase_date',
        'purchase_price',
        'condition',
        'status',
        'description',
        'image',
        'location',
        'original_location',
    ];

    protected $casts = [
        'purchase_date'  => 'date',
        'purchase_price' => 'decimal:2',
    ];

    const STATUS_AVAILABLE   = 'available';
    const STATUS_IN_USE      = 'in_use';
    const STATUS_MAINTENANCE = 'maintenance';
    const STATUS_RETIRED     = 'retired';

    const CONDITION_GOOD   = 'good';
    const CONDITION_FAIR   = 'fair';
    const CONDITION_POOR   = 'poor';
    const CONDITION_BROKEN = 'broken';

    public static function statuses(): array
    {
        return [
            self::STATUS_AVAILABLE   => 'Available',
            self::STATUS_IN_USE      => 'In Use',
            self::STATUS_MAINTENANCE => 'Maintenance',
            self::STATUS_RETIRED     => 'Retired',
        ];
    }

    public static function conditions(): array
    {
        return [
            self::CONDITION_GOOD   => 'Good',
            self::CONDITION_FAIR   => 'Fair',
            self::CONDITION_POOR   => 'Poor',
            self::CONDITION_BROKEN => 'Broken',
        ];
    }

    /**
     * Relasi ke AssetCategory
     */
    public function assetCategory()
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function loans()
    {
        return $this->hasMany(Loan::class);
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class);
    }

    public function activeLoan()
    {
        return $this->hasOne(Loan::class)->whereNull('returned_at');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_AVAILABLE   => 'success',
            self::STATUS_IN_USE      => 'primary',
            self::STATUS_MAINTENANCE => 'warning',
            self::STATUS_RETIRED     => 'secondary',
            default                  => 'secondary',
        };
    }

    public function getConditionBadgeAttribute(): string
    {
        return match ($this->condition) {
            self::CONDITION_GOOD   => 'success',
            self::CONDITION_FAIR   => 'warning',
            self::CONDITION_POOR   => 'danger',
            self::CONDITION_BROKEN => 'dark',
            default                => 'secondary',
        };
    }

    /**
     * Nama kategori (dari relasi atau fallback ke kolom category lama)
     */
    public function getCategoryNameAttribute(): string
    {
        return $this->assetCategory?->name ?? $this->attributes['category'] ?? '-';
    }
}
