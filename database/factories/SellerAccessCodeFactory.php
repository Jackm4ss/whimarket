<?php

namespace Database\Factories;

use App\Models\SellerAccessCode;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class SellerAccessCodeFactory extends Factory
{
    protected $model = SellerAccessCode::class;

    public function definition(): array
    {
        return [
            'code' => 'WHI-VIP-'.strtoupper(Str::random(6)),
            'email' => fake()->safeEmail(),
            'user_id' => null,
            'is_used' => false,
            'used_at' => null,
            'created_by' => User::factory()->admin(),
        ];
    }
}
