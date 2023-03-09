<?php

namespace App\Filament\Resources\SoftwaretypeResource\Pages;

use App\Filament\Resources\SoftwaretypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateSoftwaretype extends CreateRecord
{
    protected static string $resource = SoftwaretypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Software type created successfully';
    }
}
