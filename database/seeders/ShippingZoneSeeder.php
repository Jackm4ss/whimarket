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
                'name' => 'Zona 1 (Jabodetabek & Banten)',
                'rate' => 10000.00,
                'etd' => '1-2 hari kerja',
                'provinces' => [
                    'Daerah Khusus Ibukota Jakarta',
                    'DKI Jakarta',
                    'Jawa Barat',
                    'Banten',
                ],
                'courier_notes' => 'Ekspedisi J&T Express / SiCepat / JNE Reguler (Jalur Darat Prioritas)',
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 1,
            ],
            [
                'zone_code' => 'ZONA-2',
                'name' => 'Zona 2 (Jawa Tengah, DIY & Jawa Timur)',
                'rate' => 15000.00,
                'etd' => '2-3 hari kerja',
                'provinces' => [
                    'Jawa Tengah',
                    'Daerah Istimewa Yogyakarta',
                    'DI Yogyakarta',
                    'Jawa Timur',
                ],
                'courier_notes' => 'Jalur darat Trans-Jawa JNE Reguler / SiCepat Regular',
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 2,
            ],
            [
                'zone_code' => 'ZONA-3',
                'name' => 'Zona 3 (Sumatera, Bali & NTB)',
                'rate' => 22000.00,
                'etd' => '2-4 hari kerja',
                'provinces' => [
                    'Aceh',
                    'Sumatera Utara',
                    'Sumatera Barat',
                    'Riau',
                    'Kepulauan Riau',
                    'Jambi',
                    'Sumatera Selatan',
                    'Kepulauan Bangka Belitung',
                    'Bengkulu',
                    'Lampung',
                    'Bali',
                    'Nusa Tenggara Barat',
                ],
                'courier_notes' => 'Kargo Udara / Kapal Cepat Ferry lintas selat',
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 3,
            ],
            [
                'zone_code' => 'ZONA-4',
                'name' => 'Zona 4 (Kalimantan, Sulawesi & NTT)',
                'rate' => 32000.00,
                'etd' => '3-5 hari kerja',
                'provinces' => [
                    'Kalimantan Barat',
                    'Kalimantan Tengah',
                    'Kalimantan Selatan',
                    'Kalimantan Timur',
                    'Kalimantan Utara',
                    'Sulawesi Utara',
                    'Gorontalo',
                    'Sulawesi Tengah',
                    'Sulawesi Barat',
                    'Sulawesi Selatan',
                    'Sulawesi Tenggara',
                    'Nusa Tenggara Timur',
                ],
                'courier_notes' => 'Penerbangan logistik kargo komersial antarpulau',
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 4,
            ],
            [
                'zone_code' => 'ZONA-5',
                'name' => 'Zona 5 (Maluku, Papua & Pelosok)',
                'rate' => 48000.00,
                'etd' => '4-7 hari kerja',
                'provinces' => [
                    'Maluku',
                    'Maluku Utara',
                    'Papua',
                    'Papua Barat',
                    'Papua Barat Daya',
                    'Papua Pegunungan',
                    'Papua Selatan',
                    'Papua Tengah',
                ],
                'courier_notes' => 'Penerbangan perintis udara & armada kapal Pelni logistik kepulauan timur',
                'is_free_shipping' => false,
                'is_active' => true,
                'sort_order' => 5,
            ],
        ];

        foreach ($zones as $zoneData) {
            ShippingZone::updateOrCreate(
                ['zone_code' => $zoneData['zone_code']],
                $zoneData
            );
        }
    }
}
