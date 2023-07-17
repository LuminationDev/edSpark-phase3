<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDatabeforeFill(array $data): array
    {
        $data['Author'] = Auth::user()->full_name;
        $location = json_decode($data['event_location']);
        $data['url'] = $location && isset($location->url) ? $location->url : '';
        $data['address'] = $location && isset($location->address) ? $location->address : '';
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['post_modified'] = Carbon::now();
        $data['event_location'] = [];
        if(isset($data['url'])){
            $data['event_location']['url'] = $data['url'];
        }
        if(isset($data['address'])){
            $data['event_location']['address'] = $data['address'];
        }
        $data['event_location'] = json_encode($data['event_location']);

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getSavedNotificationTitle(): ?string
    {
        return 'Event updated successfully';
    }
}
