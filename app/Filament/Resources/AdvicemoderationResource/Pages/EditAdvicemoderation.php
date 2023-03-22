<?php

namespace App\Filament\Resources\AdvicemoderationResource\Pages;

use App\Filament\Resources\AdvicemoderationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class EditAdvicemoderation extends EditRecord
{
    protected static string $resource = AdvicemoderationResource::class;

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
        return 'Advice moderated successfully';
    }
}
