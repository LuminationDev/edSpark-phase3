<?php

namespace App\Filament\Resources\AdviceResource\Pages;

use App\Filament\Resources\AdviceResource;
use App\Helpers\NotificationActionType;
use App\Helpers\NotificationResource;
use App\Helpers\NotificationResourceType;
use App\Models\Advice;
use App\Models\User;
use App\Notifications\ResourceCreated;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

use App\Models\Advicemeta;
use Illuminate\Support\Facades\Notification;

class CreateAdvice extends CreateRecord
{
    protected static string $resource = AdviceResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['post_date'] = Carbon::now();
        $data['post_modified'] = Carbon::now();
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record =  parent::handleRecordCreation($data);
        //handle tags
        if (isset($data['tags'])) {
            $thatEvent = Advice::find($record->id);
            $thatEvent->attachTags($data['tags']);
        }
        //end of tag code

        // send notification here
        $currentUser = Auth::user();
        $usersExceptCurrent = User::whereKeyNot($currentUser)->get();
        $notificationObject = new NotificationResource($record->id, $record->post_title, $currentUser->id, NotificationResourceType::ADVICE, NotificationActionType::PUBLISHED);
        foreach ($usersExceptCurrent as $eachUser){
            $eachUser->notify(new ResourceCreated($notificationObject));
        }
        // end of notification code


        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Advice created successfully';
    }
}
