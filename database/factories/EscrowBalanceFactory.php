<?php

namespace Database\Factories;

use App\Models\EscrowBalance;
use App\Models\Order;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;

class EscrowBalanceFactory extends Factory
{
    protected $model = EscrowBalance::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'seller_id' => Seller::factory(),
            'amount' => 250000,
            'is_released' => false,
            'released_at' => null,
        ];
    }
}
