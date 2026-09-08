<?php

namespace Database\Factories;

use App\Models\Dispute;
use App\Models\Order;
use App\Models\User;
use App\States\Dispute\OpenDispute;
use Illuminate\Database\Eloquent\Factories\Factory;

class DisputeFactory extends Factory
{
    protected $model = Dispute::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'buyer_id' => User::factory()->buyer(),
            'reason' => 'Barang Rusak / Cacat',
            'description' => fake()->paragraph(),
            'buyer_evidence_paths' => ['disputes/evidence_1.jpg'],
            'video_unboxing_path' => null,
            'seller_response' => null,
            'seller_evidence_paths' => null,
            'status' => OpenDispute::class,
            'resolution_notes' => null,
            'resolved_by' => null,
            'resolved_at' => null,
        ];
    }
}
