<?php

namespace App\States\Order;

class Cancelled extends OrderStatusState
{
    public static string $name = 'cancelled';

    public function label(): string
    {
        return 'Dibatalkan';
    }

    public function badgeColor(): string
    {
        return 'rose';
    }
}
