<?php

namespace App\Services;

use App\Models\ShippingZone;
use Illuminate\Support\Facades\Cache;

class ShippingRateService
{
    /**
     * Default fallback rate if province is unknown or unconfigured.
     */
    public const DEFAULT_RATE = 15000;

    /**
     * Calculate shipping rate, zone label, and estimated delivery time based on province.
     * Sourced from the ShippingZone database table with high-speed 24-hour caching.
     *
     * @return array{cost: int, zone: string, etd: string, courier_label: string, is_free_shipping: bool, original_rate: int}
     */
    public static function calculate(string|int|null $provinceNameOrCode): array
    {
        $input = strtolower(trim((string) $provinceNameOrCode));
        // Clear cache key if needed or retrieve active zones from cache/database
        $zones = Cache::remember('shipping_zones_active', now()->addHours(24), function () {
            try {
                return ShippingZone::where('is_active', true)
                    ->orderBy('sort_order')
                    ->get()
                    ->toArray();
            } catch (\Throwable $e) {
                return [];
            }
        });

        if (! empty($zones) && $input !== '') {
            foreach ($zones as $zone) {
                $provinces = (array) ($zone['provinces'] ?? []);
                foreach ($provinces as $p) {
                    $norm = strtolower(trim((string) $p));
                    if ($norm === '') {
                        continue;
                    }

                    // Match exact, contains, or reversed contains
                    if ($norm === $input || str_contains($input, $norm) || str_contains($norm, $input) || (str_contains($input, 'jakarta') && str_contains($norm, 'jakarta'))) {
                        $rate = (int) $zone['rate'];
                        $cost = ! empty($zone['is_free_shipping']) ? 0 : $rate;

                        return [
                            'cost' => $cost,
                            'zone' => $zone['name'],
                            'etd' => $zone['etd'] ?? '2-4 hari kerja',
                            'courier_label' => 'Reguler (J&T / JNE / SiCepat)',
                            'is_free_shipping' => (bool) ($zone['is_free_shipping'] ?? false),
                            'original_rate' => $rate,
                        ];
                    }
                }
            }
        }

        // Fallback static calculation if table is empty or province not found in active zones
        return self::fallbackCalculate($input);
    }

    /**
     * Fallback calculation matrix.
     *
     * @return array{cost: int, zone: string, etd: string, courier_label: string, is_free_shipping: bool, original_rate: int}
     */
    protected static function fallbackCalculate(string $input): array
    {
        if ($input === '') {
            return [
                'cost' => self::DEFAULT_RATE,
                'zone' => 'Zona 1 (Jabodetabek)',
                'etd' => '1-3 hari kerja',
                'courier_label' => 'Reguler (J&T / JNE / SiCepat)',
                'is_free_shipping' => false,
                'original_rate' => self::DEFAULT_RATE,
            ];
        }

        if (str_contains($input, 'jakarta') || str_contains($input, 'dki') || str_contains($input, 'banten') || str_contains($input, 'jawa barat') || str_contains($input, 'jabar') || in_array($input, ['31', '32', '36'], true)) {
            return [
                'cost' => 15000,
                'zone' => 'Zona 1 (Jabodetabek & Jabar)',
                'etd' => '1-2 hari kerja',
                'courier_label' => 'Reguler (J&T / JNE / SiCepat)',
                'is_free_shipping' => false,
                'original_rate' => 15000,
            ];
        }

        if (str_contains($input, 'jawa tengah') || str_contains($input, 'jateng') || str_contains($input, 'yogyakarta') || str_contains($input, 'jogja') || str_contains($input, 'jawa timur') || str_contains($input, 'jatim') || in_array($input, ['33', '34', '35'], true)) {
            return [
                'cost' => 22000,
                'zone' => 'Zona 2 (Jawa Tengah, DIY, Jawa Timur)',
                'etd' => '2-3 hari kerja',
                'courier_label' => 'Reguler (J&T / JNE / SiCepat)',
                'is_free_shipping' => false,
                'original_rate' => 22000,
            ];
        }

        if (str_contains($input, 'sumatera') || str_contains($input, 'sumatra') || str_contains($input, 'aceh') || str_contains($input, 'riau') || str_contains($input, 'jambi') || str_contains($input, 'bengkulu') || str_contains($input, 'lampung') || str_contains($input, 'bangka') || str_contains($input, 'belitung') || str_contains($input, 'bali') || str_contains($input, 'nusa tenggara barat') || str_contains($input, 'ntb') || in_array($input, ['11', '12', '13', '14', '15', '16', '17', '18', '19', '21', '51', '52'], true)) {
            return [
                'cost' => 35000,
                'zone' => 'Zona 3 (Sumatera, Bali & NTB)',
                'etd' => '2-4 hari kerja',
                'courier_label' => 'Reguler (J&T / JNE / SiCepat)',
                'is_free_shipping' => false,
                'original_rate' => 35000,
            ];
        }

        if (str_contains($input, 'kalimantan') || str_contains($input, 'sulawesi') || str_contains($input, 'gorontalo') || str_contains($input, 'nusa tenggara timur') || str_contains($input, 'ntt') || in_array($input, ['61', '62', '63', '64', '65', '71', '72', '73', '74', '75', '76', '53'], true)) {
            return [
                'cost' => 48000,
                'zone' => 'Zona 4 (Kalimantan, Sulawesi & NTT)',
                'etd' => '3-5 hari kerja',
                'courier_label' => 'Reguler (J&T / JNE / SiCepat)',
                'is_free_shipping' => false,
                'original_rate' => 48000,
            ];
        }

        if (str_contains($input, 'maluku') || str_contains($input, 'papua') || in_array($input, ['81', '82', '91', '92', '93', '94', '95', '96'], true)) {
            return [
                'cost' => 80000,
                'zone' => 'Zona 5 (Maluku & Papua)',
                'etd' => '4-7 hari kerja',
                'courier_label' => 'Reguler (J&T / JNE / SiCepat)',
                'is_free_shipping' => false,
                'original_rate' => 80000,
            ];
        }

        return [
            'cost' => self::DEFAULT_RATE,
            'zone' => 'Zona Standar',
            'etd' => '2-4 hari kerja',
            'courier_label' => 'Reguler (J&T / JNE / SiCepat)',
            'is_free_shipping' => false,
            'original_rate' => self::DEFAULT_RATE,
        ];
    }
}
