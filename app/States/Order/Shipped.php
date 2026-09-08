<?php

namespace App\States\Order;

class Shipped extends OrderStatusState
{
    public static string $name = 'shipped';

    public function label(): string
    {
        return 'Sedang Dikirim';
    }

    public function badgeColor(): string
    {
        return 'purple';
    }
}
