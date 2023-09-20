<?php

namespace App\Filament\Resources\CommunitytypeResource\Pages;

use App\Filament\Resources\CommunitytypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommunitytype extends EditRecord
{
    protected static string $resource = CommunitytypeResource::class;

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
        return 'Community type updated successfully';
    }
}
