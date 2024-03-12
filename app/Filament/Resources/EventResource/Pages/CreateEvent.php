<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Helpers\NotificationActionType;
use App\Helpers\NotificationResourceType;
use App\Models\Event;
use App\Models\User;
use App\Traits\UseNotification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateEvent extends CreateRecord
{
    use useNotification;
    private string $notificationResourceType = NotificationResourceType::EVENT;

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

        // send notification here
        $currentUser = Auth::user();
        $usersExceptCurrent = User::whereKeyNot($currentUser)->get();

        foreach ($usersExceptCurrent as $eachUser){
            $this->sendNotification($eachUser, $record->id, $record->event_title, $currentUser->id, $this->notificationResourceType, NotificationActionType::PUBLISHED);
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
