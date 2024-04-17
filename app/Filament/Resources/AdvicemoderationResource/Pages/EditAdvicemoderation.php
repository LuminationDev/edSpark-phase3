<?php

namespace App\Filament\Resources\AdvicemoderationResource\Pages;

use App\Filament\Resources\AdvicemoderationResource;
use Filament\Actions\Action;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class EditAdvicemoderation extends EditRecord
{
    protected static string $resource = AdvicemoderationResource::class;

    protected function getHeaderActions(): array
    {
        $baseUrl = env('APP_URL');
        return [
            Action::make('preview')
                ->url(fn ($record) => rtrim($baseUrl, '/') . '/guide/resources/'. $record->id . '/' . $record->title .'?preview=true&source=filament')
                ->openUrlInNewTab()
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['updated_at'] = Carbon::now();
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Advice moderated successfully';
    }
}
