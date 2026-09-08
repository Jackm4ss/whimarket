<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = 250000;
        $qty = 1;

        return [
            'order_id' => Order::factory(),
            'product_variant_id' => ProductVariant::factory(),
            'product_name_snapshot' => fake()->words(3, true),
            'variant_name_snapshot' => 'Size M',
            'price_snapshot' => $price,
            'quantity' => $qty,
            'subtotal' => $price * $qty,
        ];
    }
}
