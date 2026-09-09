<?php

namespace App\States\Order;

class PaymentVerification extends OrderStatusState
{
    public static string $name = 'payment_verification';

    public function label(): string
    {
        return 'Sedang Di Verifikasi Pembayaran Oleh Admin';
    }

    public function badgeColor(): string
    {
        return 'purple';
    }
}
