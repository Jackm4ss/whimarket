<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use Illuminate\Database\Seeder;

class ShippingZoneSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'zone_code' => 'ZONA-1',
                'name' => 'Zona 1 (Jabodetabek & Jabar)',
                'rate' => 15000,
                'etd' => '1-2 hari kerja',
                'provinces' => [
                    'Daerah Khusus Ibukota Jakarta',
                    'DKI Jakarta',
                    'Jawa Barat',
                    'Banten',
                ],
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 1,
                'courier_notes' => 'Ekspedisi reguler terpercaya (J&T / JNE / SiCepat)',
            ],
            [
                'zone_code' => 'ZONA-2',
                'name' => 'Zona 2 (Jawa Tengah, DIY, Jawa Timur)',
                'rate' => 22000,
                'etd' => '2-3 hari kerja',
                'provinces' => [
                    'Jawa Tengah',
                    'Daerah Istimewa Yogyakarta',
                    'Jawa Timur',
                ],
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 2,
                'courier_notes' => 'Pengiriman antar kota lintas pulau Jawa',
            ],
            [
                'zone_code' => 'ZONA-3',
                'name' => 'Zona 3 (Sumatera, Bali & NTB)',
                'rate' => 35000,
                'etd' => '2-4 hari kerja',
                'provinces' => [
                    'Aceh',
                    'Sumatera Utara',
                    'Sumatera Barat',
                    'Riau',
                    'Jambi',
                    'Sumatera Selatan',
                    'Bengkulu',
                    'Lampung',
                    'Kepulauan Bangka Belitung',
                    'Kepulauan Riau',
                    'Bali',
                    'Nusa Tenggara Barat',
                ],
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 3,
                'courier_notes' => 'Pengiriman jalur udara/laut reguler',
            ],
            [
                'zone_code' => 'ZONA-4',
                'name' => 'Zona 4 (Kalimantan, Sulawesi & NTT)',
                'rate' => 48000,
                'etd' => '3-5 hari kerja',
                'provinces' => [
                    'Kalimantan Barat',
                    'Kalimantan Tengah',
                    'Kalimantan Selatan',
                    'Kalimantan Timur',
                    'Kalimantan Utara',
                    'Sulawesi Utara',
                    'Sulawesi Tengah',
                    'Sulawesi Selatan',
                    'Sulawesi Tenggara',
                    'Gorontalo',
                    'Sulawesi Barat',
                    'Nusa Tenggara Timur',
                ],
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 4,
                'courier_notes' => 'Pengiriman kargo udara terpercaya',
            ],
            [
                'zone_code' => 'ZONA-5',
                'name' => 'Zona 5 (Maluku & Papua)',
                'rate' => 80000,
                'etd' => '4-7 hari kerja',
                'provinces' => [
                    'Maluku',
                    'Maluku Utara',
                    'Papua',
                    'Papua Barat',
                    'Papua Selatan',
                    'Papua Tengah',
                    'Papua Pegunungan',
                    'Papua Barat Daya',
                ],
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 5,
                'courier_notes' => 'Pengiriman terisolasi Indonesia Timur',
            ],
        ];

        foreach ($zones as $data) {
            ShippingZone::updateOrCreate(
                ['zone_code' => $data['zone_code']],
                $data
            );
        }
    }
}
