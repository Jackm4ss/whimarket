<?php

namespace App\Policies;

use App\Models\Dispute;
use App\Models\User;

class DisputePolicy
{
    public function view(User $user, Dispute $dispute): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        if ($user->id === $dispute->buyer_id) {
            return true;
        }

        if ($user->seller && $user->seller->id === $dispute->order->seller_id) {
            return true;
        }

        return false;
    }

    public function respond(User $user, Dispute $dispute): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->seller && $user->seller->id === $dispute->order->seller_id;
    }

    public function resolve(User $user, Dispute $dispute): bool
    {
        return $user->isAdmin();
    }
}
