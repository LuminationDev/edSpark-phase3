<?php

namespace App\Filament\Resources\CommunitymoderationResource\Pages;

use App\Filament\Resources\CommunitymoderationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditCommunitymoderation extends EditRecord
{
    protected static string $resource = CommunitymoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['modified_at'] = Carbon::now();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Community moderated successfully';
    }
}
