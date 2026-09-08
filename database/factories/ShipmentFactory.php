<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'courier_name' => 'J&T Express',
            'tracking_number' => 'JT'.fake()->numerify('##########'),
            'pre_shipment_photo_path' => 'shipments/pre_shipment_demo.jpg',
            'receipt_photo_path' => 'shipments/receipt_demo.jpg',
            'shipped_at' => now(),
            'delivered_at' => null,
        ];
    }
}
