<?php

namespace App\Models;

use App\Enums\SellerStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
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
        'banner_image',
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

    public function followers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'seller_followers', 'seller_id', 'user_id')->withTimestamps();
    }

    public function sellerFollowers(): HasMany
    {
        return $this->hasMany(SellerFollower::class);
    }

    public function productWishlists(): HasManyThrough
    {
        return $this->hasManyThrough(Wishlist::class, Product::class, 'seller_id', 'product_id');
    }

    public function getFollowersCountFormattedAttribute(): string
    {
        $isDemo = in_array(strtolower($this->username), ['rachelvennya', 'celloszx', 'raisa6690', 'fuji_an', 'windahbasudara', 'bramastavrl']);
        $realCount = $this->followers()->count();
        $total = $isDemo ? (12400 + $realCount) : $realCount;

        if ($total >= 1000) {
            return number_format($total / 1000, 1, ',', '.').'rb';
        }

        return (string) $total;
    }

    public function isVerified(): bool
    {
        return $this->status === SellerStatus::VERIFIED;
    }

    public function getBannerUrlAttribute(): string
    {
        if (! empty($this->banner_image)) {
            return $this->banner_image;
        }

        return '/assets/seller-banner-rachel.png';
    }

    public function getAvatarUrlAttribute(): string
    {
        if (! empty($this->user?->avatar)) {
            return $this->user->avatar;
        }

        $initial = strtoupper(substr($this->store_name ?: 'W', 0, 1));

        return 'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" rx="64" fill="%23F3EEFF"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-weight="900" font-size="52" fill="%234F26A6">'.$initial.'</text></svg>';
    }
}
