<?php

namespace App\Filament\Resources\AdvicetypeResource\Pages;

use App\Filament\Resources\AdvicetypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAdvicetype extends EditRecord
{
    protected static string $resource = AdvicetypeResource::class;

    protected function getActions(): array
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
        return 'Advice type updated successfully';
    }
}
