<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Seller;
use App\Models\User;
use App\States\Order\PendingPayment;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $amount = fake()->randomElement([150000, 300000, 500000]);
        $shipping = 15000;

        return [
            'order_number' => 'WHI-'.date('Ymd').'-'.strtoupper(fake()->bothify('####??')),
            'buyer_id' => User::factory()->buyer(),
            'seller_id' => Seller::factory(),
            'address_snapshot' => [
                'recipient_name' => fake()->name(),
                'phone' => fake()->phoneNumber(),
                'full_address' => fake()->streetAddress(),
                'city' => 'Jakarta Selatan',
                'province' => 'DKI Jakarta',
                'postal_code' => '12190',
            ],
            'total_amount' => $amount,
            'shipping_cost' => $shipping,
            'grand_total' => $amount + $shipping,
            'status' => PendingPayment::class,
            'inspection_deadline_at' => null,
            'completed_at' => null,
        ];
    }
}
