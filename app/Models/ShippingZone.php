<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class ShippingZone extends Model
{
    use HasFactory;

    protected $fillable = [
        'zone_code',
        'name',
        'rate',
        'etd',
        'provinces',
        'is_free_shipping',
        'is_active',
        'sort_order',
        'courier_notes',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'provinces' => 'array',
            'is_free_shipping' => 'boolean',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::saved(function () {
            Cache::forget('shipping_zones_active');
        });

        static::deleted(function () {
            Cache::forget('shipping_zones_active');
        });
    }

    /**
     * Get effective rate (0 if promo free shipping is active).
     */
    public function getEffectiveRateAttribute(): float
    {
        return $this->is_free_shipping ? 0.0 : (float) $this->rate;
    }
}
