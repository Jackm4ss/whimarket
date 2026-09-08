<?php

namespace App\States\Order;

use Spatie\ModelStates\State;
use Spatie\ModelStates\StateConfig;

abstract class OrderStatusState extends State
{
    abstract public function label(): string;

    abstract public function badgeColor(): string;

    public static function config(): StateConfig
    {
        return parent::config()
            ->default(PendingPayment::class)
            ->allowTransition(PendingPayment::class, PaymentVerification::class)
            ->allowTransition(PendingPayment::class, Cancelled::class)
            ->allowTransition(PaymentVerification::class, Paid::class)
            ->allowTransition(PaymentVerification::class, PendingPayment::class)
            ->allowTransition(PaymentVerification::class, Cancelled::class)
            ->allowTransition(Paid::class, Processing::class)
            ->allowTransition(Paid::class, Shipped::class)
            ->allowTransition(Processing::class, Shipped::class)
            ->allowTransition(Shipped::class, Delivered::class)
            ->allowTransition(Delivered::class, Completed::class)
            ->allowTransition(Delivered::class, Disputed::class)
            ->allowTransition(Disputed::class, Completed::class)
            ->allowTransition(Disputed::class, Cancelled::class);
    }
}
