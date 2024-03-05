<?php

namespace App\Filament\Resources\AdviceResource\Pages;

use App\Filament\Resources\AdviceResource;
use App\Helpers\NotificationResource;
use App\Models\Advice;
use App\Models\Event;
use App\Models\User;
use App\Notifications\ResourceCreated;
use Filament\Pages\Actions;
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

        // send notification here
        // users to send notification too
        $currentUser = Auth::user();
        $usersExceptCurrent = User::whereKeyNot($currentUser)->get();
//        dd($usersExceptCurrent);

        // create the notification item
        $notificationObject = new NotificationResource($record->id, 'test title', 10005, 'test', 'create');
//        Notification::send($usersExceptCurrent,new ResourceCreated($notificationObject));
//        User::find(10005)->notify(new ResourceCreated($notificationObject));

        foreach ($usersExceptCurrent as $eachUser){
            $eachUser->notify(new ResourceCreated($notificationObject));
        }
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
