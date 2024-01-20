<?php

namespace App\Filament\Resources\PartnermoderationResource\Pages;

use App\Filament\Resources\PartnermoderationResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\EditRecord;

class EditPartnermoderation extends EditRecord
{
    protected static string $resource = PartnermoderationResource::class;

    protected function getHeaderActions(): array
    {
        $baseUrl = env('APP_URL');

        return [
            Action::make('preview')
                ->url(fn ($record) => rtrim($baseUrl, '/') . '/partner/'. $record->user_id . '?preview=true&source=filament')
                ->openUrlInNewTab()        ];
    }
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Partner profile moderated successfully';
    }
}
