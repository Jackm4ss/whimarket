<?php

namespace App\States\Order;

class Paid extends OrderStatusState
{
    public static string $name = 'paid';

    public function label(): string
    {
        return 'Pembayaran Diterima';
    }

    public function badgeColor(): string
    {
        return 'emerald';
    }
}
