<?php

namespace App\Filament\Resources\AdviceResource\Pages;

use App\Filament\Resources\AdviceResource;
use App\Helpers\NotificationActionType;
use App\Helpers\NotificationResource;
use App\Helpers\NotificationResourceType;
use App\Helpers\StatusHelpers;
use App\Models\Advice;
use App\Models\User;
use App\Notifications\ResourceCreated;
use App\Traits\UseNotification;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreateAdvice extends CreateRecord
{
    use useNotification;

    private string $notificationResourceType = NotificationResourceType::ADVICE;

    protected static string $resource = AdviceResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $record = parent::handleRecordCreation($data);
        //handle tags
        if (isset($data['tags'])) {
            $thatEvent = Advice::find($record->id);
            $thatEvent->attachTags($data['tags']);
        }
        //end of tag code

        // send notification here
        $currentUser = Auth::user();
        $usersExceptCurrent = User::whereKeyNot($currentUser)->get();

        foreach ($usersExceptCurrent as $eachUser) {
            $this->sendNotification($eachUser, $record->id, $record->title, $currentUser->id, $this->notificationResourceType, NotificationActionType::PUBLISHED);
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
