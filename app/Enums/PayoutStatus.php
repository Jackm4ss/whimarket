<?php

namespace App\Enums;

enum PayoutStatus: string
{
    case PENDING = 'pending';
    case PROCESSING = 'processing';
    case PAID = 'paid';
    case FAILED = 'failed';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Menunggu Transfer',
            self::PROCESSING => 'Sedang Diproses',
            self::PAID => 'Telah Dicairkan',
            self::FAILED => 'Gagal',
        };
    }
}
