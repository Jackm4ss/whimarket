<?php

namespace App\Enums;

enum Gender: string
{
    case PRIA = 'pria';
    case WANITA = 'wanita';

    public function label(): string
    {
        return match ($this) {
            self::PRIA => 'Pria',
            self::WANITA => 'Wanita',
        };
    }
}
