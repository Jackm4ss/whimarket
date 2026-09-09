<?php

namespace App\Services;

use App\Models\PlatformSetting;

class PlatformFeeService
{
    /**
     * Default fallback fee if not configured in database.
     */
    public const DEFAULT_FEE = 2000.0;

    /**
     * Get the active admin fee in Rupiah.
     */
    public static function getFee(): float
    {
        return PlatformSetting::getAdminFee();
    }

    /**
     * Check if admin fee is active.
     */
    public static function isActive(): bool
    {
        return PlatformSetting::isAdminFeeActive();
    }

    /**
     * Calculate transaction grand total including admin fee.
     *
     * @return array{subtotal: float, shipping_cost: float, admin_fee: float, grand_total: float}
     */
    public static function calculate(float $subtotal, float $shippingCost): array
    {
        $adminFee = self::getFee();
        $grandTotal = $subtotal + $shippingCost + $adminFee;

        return [
            'subtotal' => $subtotal,
            'shipping_cost' => $shippingCost,
            'admin_fee' => $adminFee,
            'grand_total' => $grandTotal,
        ];
    }
}
