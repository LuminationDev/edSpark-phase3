<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateEvent extends CreateRecord
{
    protected static string $resource = EventResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {

        if(isset($data['url'])){
            $data['event_location']['url'] = $data['url'];
        }
        if(isset($data['address'])){
            $data['event_location']['address'] = $data['address'];
        }
        $data['event_location'] = isset($data['event_location']) ? json_encode($data['event_location']) : "";
        $data['post_date'] = Carbon::now();
        $data['post_modified'] = Carbon::now();
        return $data;
    }

    protected function handleRecordCreation(array $data): \Illuminate\Database\Eloquent\Model
    {
        // Create the main record.
        $record = parent::handleRecordCreation($data);

        // Handle tags.
        if (isset($data['tags'])) {
            $thatEvent = Event::find($record->id);
            $thatEvent->attachTags($data['tags']);
        }

        return $record;
    }



    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Event created successfully';
    }
}
