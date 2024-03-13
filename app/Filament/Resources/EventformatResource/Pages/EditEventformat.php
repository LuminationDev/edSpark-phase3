<?php

namespace App\Filament\Resources\EventformatResource\Pages;

use App\Filament\Resources\EventformatResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEventformat extends EditRecord
{
    protected static string $resource = EventformatResource::class;

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
        return 'Event format updated successfully';
    }
}
