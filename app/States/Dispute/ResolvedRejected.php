<?php

namespace App\States\Dispute;

class ResolvedRejected extends DisputeStatusState
{
    public static string $name = 'resolved_rejected';

    public function label(): string
    {
        return 'Dispute Ditolak (Order Selesai)';
    }

    public function badgeColor(): string
    {
        return 'emerald';
    }
}
