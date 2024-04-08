<?php

namespace App\Filament\Resources\SoftwaremoderationResource\Pages;

use App\Filament\Resources\SoftwaremoderationResource;
use Filament\Actions\Action;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Carbon\Carbon;

class EditSoftwaremoderation extends EditRecord
{
    protected static string $resource = SoftwaremoderationResource::class;

    protected function getHeaderActions(): array
    {
        $baseUrl = env('APP_URL');
        return [
            Action::make('preview')
                ->url(fn ($record) => rtrim($baseUrl, '/') . '/software/resources/'. $record->id . '/' . $record->title .'?preview=true&source=filament')
                ->openUrlInNewTab()
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
        return 'Software moderated successfully';
    }
}
