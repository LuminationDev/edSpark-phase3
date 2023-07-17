<?php

namespace App\Filament\Resources\PartnerprofileResource\Pages;

use App\Filament\Resources\PartnerprofileResource;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreatePartnerprofile extends CreateRecord
{
    protected static string $resource = PartnerprofileResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_at'] = Carbon::now();
        $data['updated_at'] = Carbon::now();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Partner profile created successfully';
    }
}
