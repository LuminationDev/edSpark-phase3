<?php

namespace App\Filament\Resources\SoftwaremoderationResource\Pages;

use App\Filament\Resources\SoftwaremoderationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;

class EditSoftwaremoderation extends EditRecord
{
    protected static string $resource = SoftwaremoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['post_modified'] = Carbon::now();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Software moderated successfully';
    }
}
