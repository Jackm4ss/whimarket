<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EscrowBalance extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'seller_id',
        'amount',
        'is_released',
        'released_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'is_released' => 'boolean',
            'released_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class);
    }
}
