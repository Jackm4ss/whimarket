<?php

namespace App\States\Dispute;

class SellerResponded extends DisputeStatusState
{
    public static string $name = 'seller_responded';

    public function label(): string
    {
        return 'Tanggapan Seller Masuk';
    }

    public function badgeColor(): string
    {
        return 'purple';
    }
}
