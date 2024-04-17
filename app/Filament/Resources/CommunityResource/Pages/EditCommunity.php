<?php

namespace App\Filament\Resources\CommunityResource\Pages;

use App\Filament\Resources\CommunityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EditCommunity extends EditRecord
{
    protected static string $resource = CommunityResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDatabeforeFill(array $data): array
    {
        $data['Author'] = Auth::user()->full_name;

        return $data;
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
        return 'Community updated successfully';
    }
}
