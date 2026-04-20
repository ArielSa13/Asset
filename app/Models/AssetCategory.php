<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetCategory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'prefix',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function assets()
    {
        return $this->hasMany(Asset::class, 'category_id');
    }

    /**
     * Generate kode aset berikutnya berdasarkan prefix kategori ini.
     * Memperhitungkan soft-deleted asset agar tidak tabrakan.
     */
    public function generateCode(): string
    {
        $prefix   = strtoupper($this->prefix);
        $lastCode = Asset::withTrashed()
            ->where('code', 'like', $prefix . '-%')
            ->orderByRaw('CAST(SUBSTRING_INDEX(code, "-", -1) AS UNSIGNED) DESC')
            ->value('code');

        $lastNumber = $lastCode
            ? (int) substr($lastCode, strrpos($lastCode, '-') + 1)
            : 0;

        return $prefix . '-' . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Scope: hanya kategori aktif
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
