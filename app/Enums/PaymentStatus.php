<?php

namespace App\Enums;

enum PaymentStatus: string
{
    case UNPAID = 'unpaid';
    case PENDING_REVIEW = 'pending_review';
    case VERIFIED = 'verified';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::UNPAID => 'Belum Dibayar',
            self::PENDING_REVIEW => 'Menunggu Verifikasi',
            self::VERIFIED => 'Terverifikasi / Lunas',
            self::REJECTED => 'Bukti Ditolak',
        };
    }
}
