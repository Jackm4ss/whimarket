<?php

namespace App\Enums;

enum ProductCondition: string
{
    case BRAND_NEW = 'brand_new';
    case LIKE_NEW = 'like_new';
    case GENTLY_USED = 'gently_used';

    public function label(): string
    {
        return match ($this) {
            self::BRAND_NEW => 'Baru (Brand New)',
            self::LIKE_NEW => 'Seperti Baru (Like New)',
            self::GENTLY_USED => 'Pernah Dipakai (Gently Used)',
        };
    }
}
