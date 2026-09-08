<?php

namespace App\Filament\Resources\Sellers\Pages;

use App\Filament\Resources\Sellers\SellerResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ManageRecords;

class ManageSellers extends ManageRecords
{
    protected static string $resource = SellerResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
