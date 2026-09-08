<?php

namespace App\Enums;

enum ProductStatus: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::ACTIVE => 'Aktif',
            self::INACTIVE => 'Nonaktif',
            self::REJECTED => 'Ditolak',
        };
    }
}
