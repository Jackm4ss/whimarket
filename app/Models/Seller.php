<?php

namespace App\Models;

use App\Enums\SellerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Seller extends Model
{
    use HasFactory, HasSlug;

    protected $fillable = [
        'user_id',
        'store_name',
        'username',
        'bio',
        'bank_name',
        'bank_account_number',
        'bank_account_name',
        'status',
        'verified_at',
        'rejection_reason',
    ];

    protected function casts(): array
    {
        return [
            'status' => SellerStatus::class,
            'verified_at' => 'datetime',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('store_name')
            ->saveSlugsTo('username')
            ->doNotGenerateSlugsOnUpdate();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'seller_id');
    }

    public function escrowBalances(): HasMany
    {
        return $this->hasMany(EscrowBalance::class);
    }

    public function payouts(): HasMany
    {
        return $this->hasMany(Payout::class);
    }

    public function isVerified(): bool
    {
        return $this->status === SellerStatus::VERIFIED;
    }
}
