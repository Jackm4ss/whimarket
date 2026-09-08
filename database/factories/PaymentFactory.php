<?php

namespace Database\Factories;

use App\Enums\PaymentStatus;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'bank_destination' => 'BCA',
            'sender_bank_name' => 'BCA',
            'sender_account_name' => fake()->name(),
            'proof_path' => 'payments/proof_demo.jpg',
            'amount' => 265000,
            'status' => PaymentStatus::PENDING_REVIEW,
            'verified_by' => null,
            'verified_at' => null,
            'rejection_reason' => null,
        ];
    }
}
