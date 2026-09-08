<?php

namespace Database\Factories;

use App\Enums\ProductCondition;
use App\Enums\ProductStatus;
use App\Models\Category;
use App\Models\Product;
use App\Models\Seller;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $name = fake()->words(3, true);

        return [
            'seller_id' => Seller::factory(),
            'category_id' => Category::factory(),
            'name' => ucwords($name),
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1000, 9999),
            'description' => fake()->paragraph(),
            'price' => fake()->randomElement([150000, 250000, 350000, 500000, 750000, 1200000]),
            'condition' => fake()->randomElement([ProductCondition::BRAND_NEW, ProductCondition::LIKE_NEW, ProductCondition::GENTLY_USED]),
            'status' => ProductStatus::ACTIVE,
        ];
    }
}
