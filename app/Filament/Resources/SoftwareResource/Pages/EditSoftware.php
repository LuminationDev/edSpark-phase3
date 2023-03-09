<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Filament\Resources\SoftwareResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class EditSoftware extends EditRecord
{
    protected static string $resource = SoftwareResource::class;

    protected function getActions(): array
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
        // dd($data); // Before mutation
        $data['post_modified'] = Carbon::now();
        // dd($data); // After mutation
        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Software updated successfully';
    }
}
