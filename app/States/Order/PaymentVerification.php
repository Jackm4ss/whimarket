<?php

namespace App\States\Order;

class PaymentVerification extends OrderStatusState
{
    public static string $name = 'payment_verification';

    public function label(): string
    {
        return 'Verifikasi Pembayaran';
    }

    public function badgeColor(): string
    {
        return 'purple';
    }
}
