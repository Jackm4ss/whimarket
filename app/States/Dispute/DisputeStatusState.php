<?php

namespace App\States\Dispute;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class DisputeStatusState extends State
{
    abstract public function label(): string;

    abstract public function badgeColor(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(OpenDispute::class)
            ->allowTransition(OpenDispute::class, SellerResponded::class)
            ->allowTransition(OpenDispute::class, UnderAdminReview::class)
            ->allowTransition(OpenDispute::class, ResolvedRefund::class)
            ->allowTransition(OpenDispute::class, ResolvedRejected::class)
            ->allowTransition(SellerResponded::class, UnderAdminReview::class)
            ->allowTransition(SellerResponded::class, ResolvedRefund::class)
            ->allowTransition(SellerResponded::class, ResolvedRejected::class)
            ->allowTransition(UnderAdminReview::class, ResolvedRefund::class)
            ->allowTransition(UnderAdminReview::class, ResolvedRejected::class);
    }
}
