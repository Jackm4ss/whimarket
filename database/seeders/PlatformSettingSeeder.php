<?php

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            [
                'key' => 'admin_fee',
                'name' => 'Biaya Layanan Pembeli (Fee Admin)',
                'value' => '2000',
                'type' => 'currency',
                'description' => 'Biaya operasional & penanganan transaksi aman escrow platform yang ditagihkan saat checkout.',
                'is_active' => true,
            ],
            [
                'key' => 'free_shipping_min_order',
                'name' => 'Minimal Belanja Subsidi Ongkir',
                'value' => '150000',
                'type' => 'currency',
                'description' => 'Batas minimum subtotal keranjang belanja pembeli untuk memenuhi syarat promo potongan ongkir.',
                'is_active' => true,
            ],
            [
                'key' => 'inspection_deadline_hours',
                'name' => 'Batas Waktu Inspeksi Barang (Auto-Complete)',
                'value' => '48',
                'type' => 'hours',
                'description' => 'Jendela waktu (dalam jam) bagi pembeli memeriksa barang tiba sebelum pesanan otomatis selesai dan saldo cair ke seller.',
                'is_active' => true,
            ],
            [
                'key' => 'payment_timeout_hours',
                'name' => 'Batas Waktu Upload Bukti Transfer',
                'value' => '24',
                'type' => 'hours',
                'description' => 'Tenggat waktu maksimal bagi pembeli menyelesaikan transfer rekening bank sebelum pesanan kadaluarsa otomatis.',
                'is_active' => true,
            ],
        ];

        foreach ($settings as $settingData) {
            PlatformSetting::updateOrCreate(
                ['key' => $settingData['key']],
                $settingData
            );
        }
    }
}
