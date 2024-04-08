<?php

namespace App\Filament\Resources\EventResource\Pages;

use App\Filament\Resources\EventResource;
use App\Models\Event;
use App\Models\User;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


class EditEvent extends EditRecord
{
    protected static string $resource = EventResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDatabeforeFill(array $data): array
    {
        // tags
        $record = parent::getRecord();
        $targetData = Event::find($record->id);
        $data['tags'] = $targetData->tags;
        if (isset($data['tags'])) {
            $tagNames = $data['tags']->pluck('name');
            $data['tags'] = $tagNames;
        }
        $data['event_format'] = $data['event_format_id'];

        //author
//        if (isset($data['author_id'])) {
//            $author = User::find($data['author_id']);
//            if ($author) {
//                $data['selected_author'] = $author->id;
//            } else {
//                $data['selected_author'] = null;
//            }
//        } else {
//            $data['selected_author'] = null;
//        }


        $location = json_decode($data['event_location']);
        $data['url'] = $location && isset($location->url) ? $location->url : '';
        $data['address'] = $location && isset($location->address) ? $location->address : '';
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // tags
        $record = parent::getRecord();
        $targetData = Event::find($record->id);
        if(isset($data['tags'])){
            $targetData->syncTags($data['tags']);
        }
        //others
        $data['modified_at'] = Carbon::now();
        $data['event_location'] = [];
        if (isset($data['url'])) {
            $data['event_location']['url'] = $data['url'];
        }
        if (isset($data['address'])) {
            $data['event_location']['address'] = $data['address'];
        }
        $data['event_location'] = json_encode($data['event_location']);
        $data['event_format_id'] = $data['event_format'];

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
