<?php

namespace App\Filament\Resources\EventmoderationResource\Pages;

use App\Filament\Resources\EventmoderationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;

class EditEventmoderation extends EditRecord
{
    protected static string $resource = EventmoderationResource::class;

    protected function getActions(): array
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
        return 'Event moderated successfully';
    }
}
