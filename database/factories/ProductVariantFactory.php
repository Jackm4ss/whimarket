<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductVariantFactory extends Factory
{
    protected $model = ProductVariant::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'name' => fake()->randomElement(['Size S', 'Size M', 'Size L', 'Size XL', 'All Size', 'Hitam', 'Putih']),
            'sku' => 'WHI-'.strtoupper(fake()->bothify('??-####')),
            'price' => fake()->randomElement([150000, 250000, 350000, 500000]),
            'stock' => fake()->numberBetween(1, 10),
        ];
    }
}
