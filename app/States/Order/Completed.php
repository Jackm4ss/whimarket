<?php

namespace App\States\Order;

class Completed extends OrderStatusState
{
    public static string $name = 'completed';

    public function label(): string
    {
        return 'Selesai';
    }

    public function badgeColor(): string
    {
        return 'emerald';
    }
}
