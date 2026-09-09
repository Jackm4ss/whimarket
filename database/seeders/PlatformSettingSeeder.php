<?php

namespace Database\Seeders;

use App\Models\PlatformSetting;
use Illuminate\Database\Seeder;

class PlatformSettingSeeder extends Seeder
{
    public function run(): void
    {
        PlatformSetting::updateOrCreate(
            ['key' => 'admin_fee'],
            [
                'name' => 'Biaya Layanan Pembeli (Fee Admin)',
                'value' => '2000',
                'type' => 'integer',
                'description' => 'Biaya administrasi dan pemeliharaan sistem escrow aman yang dikenakan kepada pembeli untuk setiap transaksi checkout pesanan.',
                'is_active' => true,
            ]
        );
    }
}
