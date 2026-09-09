<?php

namespace App\Filament\Resources\PlatformSettings\Pages;

use App\Filament\Resources\PlatformSettings\PlatformSettingResource;
use Filament\Resources\Pages\ManageRecords;

class ManagePlatformSettings extends ManageRecords
{
    protected static string $resource = PlatformSettingResource::class;

    protected ?string $subheading = 'Kelola besaran biaya layanan / fee admin marketplace secara dinamis. Nilai yang diatur di sini akan langsung diterapkan pada kalkulasi checkout pembeli.';

    protected function getHeaderActions(): array
    {
        return [];
    }
}
