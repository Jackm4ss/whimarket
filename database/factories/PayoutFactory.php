<?php

namespace Database\Factories;

use App\Enums\PayoutStatus;
use App\Models\Order;
use App\Models\Payout;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

class PayoutFactory extends Factory
{
    protected $model = Payout::class;

    public function definition(): array
    {
        return [
            'seller_id' => Seller::factory(),
            'order_id' => Order::factory(),
            'amount' => 250000,
            'bank_details_snapshot' => [
                'bank_name' => 'BCA',
                'account_number' => '1234567890',
                'account_name' => fake()->name(),
            ],
            'transfer_proof_path' => null,
            'status' => PayoutStatus::PENDING,
            'processed_by' => null,
            'processed_at' => null,
        ];
    }
}
