<?php

namespace App\Filament\Resources\SellerAccessCodes\Pages;

use App\Filament\Resources\SellerAccessCodes\SellerAccessCodeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSellerAccessCode extends EditRecord
{
    protected static string $resource = SellerAccessCodeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        if (! empty($data['is_one_time'])) {
            $data['quota_type'] = 'one_time';
        } elseif ($data['max_uses'] === null) {
            $data['quota_type'] = 'unlimited';
        } else {
            $data['quota_type'] = 'limited';
        }

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $quotaType = $data['quota_type'] ?? 'limited';

        if ($quotaType === 'one_time') {
            $data['max_uses'] = 1;
            $data['is_one_time'] = true;
        } elseif ($quotaType === 'unlimited') {
            $data['max_uses'] = null;
            $data['is_one_time'] = false;
        } else {
            $data['is_one_time'] = false;
            $data['max_uses'] = ! empty($data['max_uses']) ? (int) $data['max_uses'] : 10;
        }

        unset($data['quota_type']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
