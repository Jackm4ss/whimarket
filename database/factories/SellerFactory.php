<?php

namespace Database\Factories;

use App\Enums\SellerStatus;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SellerFactory extends Factory
{
    protected $model = Seller::class;

    public function definition(): array
    {
        $name = fake()->company();

        return [
            'user_id' => User::factory()->seller(),
            'store_name' => $name,
            'username' => Str::slug($name).'_'.fake()->unique()->numberBetween(100, 999),
            'bio' => fake()->paragraph(),
            'bank_name' => 'BCA',
            'bank_account_number' => fake()->numerify('##########'),
            'bank_account_name' => fake()->name(),
            'status' => SellerStatus::VERIFIED,
            'verified_at' => now(),
            'rejection_reason' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn () => [
            'status' => SellerStatus::PENDING,
            'verified_at' => null,
        ]);
    }
}
