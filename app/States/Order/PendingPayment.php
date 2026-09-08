<?php

namespace App\States\Order;

class PendingPayment extends OrderStatusState
{
    public static string $name = 'pending_payment';

    public function label(): string
    {
        return 'Menunggu Pembayaran';
    }

    public function badgeColor(): string
    {
        return 'amber';
    }
}
