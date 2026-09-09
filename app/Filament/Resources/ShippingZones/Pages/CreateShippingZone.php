<?php

namespace App\Filament\Resources\ShippingZones\Pages;

use App\Filament\Resources\ShippingZones\ShippingZoneResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShippingZone extends CreateRecord
{
    protected static string $resource = ShippingZoneResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
