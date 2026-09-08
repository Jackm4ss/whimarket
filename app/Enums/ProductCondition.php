<?php

namespace App\Enums;

enum ProductCondition: string
{
    case LIKE_NEW = 'like_new';
    case VERY_GOOD = 'very_good';
    case GOOD = 'good';
    case FAIR = 'fair';
    case BRAND_NEW = 'brand_new';
    case GENTLY_USED = 'gently_used';

    public function label(): string
    {
        return match ($this) {
            self::LIKE_NEW, self::BRAND_NEW => 'Seperti Baru',
            self::VERY_GOOD => 'Sangat Baik',
            self::GOOD, self::GENTLY_USED => 'Baik',
            self::FAIR => 'Cukup',
        };
    }
}
