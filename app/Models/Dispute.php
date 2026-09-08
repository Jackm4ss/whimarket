<?php

namespace App\Models;

use App\States\Dispute\DisputeStatusState;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\ModelStates\HasStates;

class Dispute extends Model implements HasMedia
{
    use HasFactory, HasStates, InteractsWithMedia;

    protected $fillable = [
        'order_id',
        'buyer_id',
        'reason',
        'description',
        'buyer_evidence_paths',
        'video_unboxing_path',
        'seller_response',
        'seller_evidence_paths',
        'status',
        'resolution_notes',
        'resolved_by',
        'resolved_at',
    ];

    protected function casts(): array
    {
        return [
            'buyer_evidence_paths' => 'array',
            'seller_evidence_paths' => 'array',
            'status' => DisputeStatusState::class,
            'resolved_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function resolver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
}
