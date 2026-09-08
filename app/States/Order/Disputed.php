<?php

namespace App\States\Order;

class Disputed extends OrderStatusState
{
    public static string $name = 'disputed';

    public function label(): string
    {
        return 'Dalam Sengketa (Dispute)';
    }

    public function badgeColor(): string
    {
        return 'rose';
    }
}
