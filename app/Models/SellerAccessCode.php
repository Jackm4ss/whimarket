<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SellerAccessCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'email',
        'max_uses',
        'used_count',
        'user_id',
        'is_used',
        'is_locked',
        'is_one_time',
        'used_at',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'max_uses' => 'integer',
            'used_count' => 'integer',
            'is_used' => 'boolean',
            'is_locked' => 'boolean',
            'is_one_time' => 'boolean',
            'used_at' => 'datetime',
        ];
    }

    public function isUnlimited(): bool
    {
        return $this->max_uses === null;
    }

    public function isAvailable(): bool
    {
        if ($this->is_locked) {
            return false;
        }

        if ($this->max_uses === null) {
            return true;
        }

        return $this->used_count < $this->max_uses;
    }

    public function resetQuota(): void
    {
        $this->update([
            'used_count' => 0,
            'is_used' => false,
            'is_locked' => false,
        ]);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
