<?php

namespace App\Models;

use App\States\Order\OrderStatusState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\ModelStates\HasStates;

class Order extends Model
{
    use HasFactory, HasStates;

    protected $fillable = [
        'order_number',
        'buyer_id',
        'seller_id',
        'address_snapshot',
        'total_amount',
        'shipping_cost',
        'admin_fee',
        'grand_total',
        'status',
        'is_stock_restored',
        'inspection_deadline_at',
        'completed_at',
    ];

    protected function casts(): array
    {
        return [
            'status' => OrderStatusState::class,
            'is_stock_restored' => 'boolean',
            'address_snapshot' => 'array',
            'total_amount' => 'decimal:2',
            'shipping_cost' => 'decimal:2',
            'admin_fee' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'inspection_deadline_at' => 'datetime',
            'completed_at' => 'datetime',
        ];
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(Seller::class, 'seller_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class);
    }

    public function shipment(): HasOne
    {
        return $this->hasOne(Shipment::class);
    }

    public function dispute(): HasOne
    {
        return $this->hasOne(Dispute::class);
    }

    public function escrowBalance(): HasOne
    {
        return $this->hasOne(EscrowBalance::class);
    }

    public function payout(): HasOne
    {
        return $this->hasOne(Payout::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function restoreStock(): bool
    {
        if ($this->is_stock_restored) {
            return false;
        }

        foreach ($this->items as $item) {
            if ($item->variant) {
                $item->variant->increment('stock', $item->quantity);
            }
        }

        $this->update(['is_stock_restored' => true]);

        return true;
    }
}
