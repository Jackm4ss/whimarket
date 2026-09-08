<?php

namespace App\Enums;

enum UserRole: string
{
    case BUYER = 'buyer';
    case SELLER = 'seller';
    case ADMIN = 'admin';

    public function label(): string
    {
        return match ($this) {
            self::BUYER => 'Buyer',
            self::SELLER => 'Seller',
            self::ADMIN => 'Admin',
        };
    }
}
