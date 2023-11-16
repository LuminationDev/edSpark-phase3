<?php

namespace App\Filament\Resources\EventmoderationResource\Pages;

use App\Filament\Resources\EventmoderationResource;
use Filament\Actions\Action;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;

class EditEventmoderation extends EditRecord
{
    protected static string $resource = EventmoderationResource::class;

    protected function getHeaderActions(): array
    {
        $baseUrl = env('APP_URL');
        return [
            Action::make('preview')
                ->url(fn ($record) => rtrim($baseUrl, '/') . '/event/resources/'. $record->id . '/' . $record->event_title .'?preview=true&source=filament')
                ->openUrlInNewTab()
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
