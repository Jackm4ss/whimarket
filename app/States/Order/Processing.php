<?php

namespace App\States\Order;

class Processing extends OrderStatusState
{
    public static string $name = 'processing';

    public function label(): string
    {
        return 'Sedang Diproses';
    }

    public function badgeColor(): string
    {
        return 'purple';
    }
}
