<?php

namespace App\States\Dispute;

class UnderAdminReview extends DisputeStatusState
{
    public static string $name = 'under_admin_review';

    public function label(): string
    {
        return 'Pemeriksaan Admin';
    }

    public function badgeColor(): string
    {
        return 'amber';
    }
}
