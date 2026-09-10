<?php

namespace App\Models;

use App\Enums\UserRole;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, HasRoles, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id',
        'role',
        'phone',
        'avatar',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'role' => UserRole::class,
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->role === UserRole::ADMIN || $this->hasRole('admin');
    }

    public function seller(): HasOne
    {
        return $this->hasOne(Seller::class);
    }

    public function addresses(): HasMany
    {
        return $this->hasMany(Address::class);
    }

    public function defaultAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('is_default', true);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'buyer_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class);
    }

    public function followedSellers(): BelongsToMany
    {
        return $this->belongsToMany(Seller::class, 'seller_followers', 'user_id', 'seller_id')->withTimestamps();
    }

    public function sellerFollowers(): HasMany
    {
        return $this->hasMany(SellerFollower::class);
    }

    public function isFollowing(Seller|int $seller): bool
    {
        $sellerId = $seller instanceof Seller ? $seller->id : $seller;

        return $this->followedSellers()->where('sellers.id', $sellerId)->exists();
    }

    public function cart(): HasOne
    {
        return $this->hasOne(Cart::class);
    }

    public function disputes(): HasMany
    {
        return $this->hasMany(Dispute::class, 'buyer_id');
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    public function isSeller(): bool
    {
        return $this->role === UserRole::SELLER || $this->hasRole('seller');
    }

    public function isAdmin(): bool
    {
        return $this->role === UserRole::ADMIN || $this->hasRole('admin');
    }

    public function getAvatarUrlAttribute(): string
    {
        if (! empty($this->avatar)) {
            return $this->avatar;
        }

        $initial = strtoupper(substr($this->name ?: 'U', 0, 1));
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 128 128"><rect width="128" height="128" rx="64" fill="#F3EEFF"/><text x="50%" y="54%" dominant-baseline="middle" text-anchor="middle" font-family="sans-serif" font-weight="900" font-size="52" fill="#4F26A6">'.$initial.'</text></svg>';

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }
}
