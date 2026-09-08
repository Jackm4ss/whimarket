<?php

namespace App\States\Dispute;

class OpenDispute extends DisputeStatusState
{
    public static string $name = 'open';

    public function label(): string
    {
        return 'Komplain Dibuka';
    }

    public function badgeColor(): string
    {
        return 'rose';
    }
}
