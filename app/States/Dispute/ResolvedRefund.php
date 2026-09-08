<?php

namespace App\States\Dispute;

class ResolvedRefund extends DisputeStatusState
{
    public static string $name = 'resolved_refund';

    public function label(): string
    {
        return 'Dispute Diterima (Refund Buyer)';
    }

    public function badgeColor(): string
    {
        return 'emerald';
    }
}
