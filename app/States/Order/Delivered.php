<?php

namespace App\States\Order;

class Delivered extends OrderStatusState
{
    public static string $name = 'delivered';

    public function label(): string
    {
        return 'Pesanan Tiba';
    }

    public function badgeColor(): string
    {
        return 'emerald';
    }
}
