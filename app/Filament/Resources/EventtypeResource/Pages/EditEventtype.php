<?php

namespace App\Filament\Resources\EventtypeResource\Pages;

use App\Filament\Resources\EventtypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventtype extends EditRecord
{
    protected static string $resource = EventtypeResource::class;

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
        return 'Event type updated successfully';
    }
}
