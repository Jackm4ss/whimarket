<?php

namespace App\Policies;

use App\Models\Seller;
use App\Models\User;

class SellerPolicy
{
    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Seller $seller): bool
    {
        return true;
    }

    public function update(User $user, Seller $seller): bool
    {
        if ($user->isAdmin()) {
            return true;
        }

        return $user->id === $seller->user_id;
    }
}
