<?php

namespace App\Filament\Resources\SoftwaretypeResource\Pages;

use App\Filament\Resources\SoftwaretypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSoftwaretype extends EditRecord
{
    protected static string $resource = SoftwaretypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Software type updated successfully';
    }
}
