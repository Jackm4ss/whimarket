<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function view(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id === $order->buyer_id) {
            return true;
        }

        if ($user->seller && $user->seller->id === $order->seller_id) {
            return true;
        }

        return false;
    }

    public function fulfill(User $user, Order $order): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->seller && $user->seller->id === $order->seller_id;
    }
}
