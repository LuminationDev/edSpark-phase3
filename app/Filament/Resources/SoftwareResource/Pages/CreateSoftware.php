<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Filament\Resources\SoftwareResource;
use App\Helpers\NotificationActionType;
use App\Helpers\NotificationResource;
use App\Helpers\NotificationResourceType;
use App\Models\Software;
use App\Models\User;
use App\Notifications\ResourceCreated;
use App\Traits\UseNotification;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreateSoftware extends CreateRecord
{
    use useNotification;

    protected static string $resource = SoftwareResource::class;

    private string $notificationResourceType = NotificationResourceType::SOFTWARE;


    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_at'] = Carbon::now();
        $data['modified_at'] = Carbon::now();
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation($data);
        $record->save();
        //handle tags
        if (isset($data['tags'])) {
            $thatEvent = Software::find($record->id);
            $thatEvent->attachTags($data['tags']);
        }

        // send notification here
        $currentUser = Auth::user();
        $usersExceptCurrent = User::whereKeyNot($currentUser)->get();

        foreach ($usersExceptCurrent as $eachUser){
            $this->sendNotification($eachUser, $record->id, $record->title, $currentUser->id, $this->notificationResourceType, NotificationActionType::PUBLISHED);
        }

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Software created successfully';
    }
}
