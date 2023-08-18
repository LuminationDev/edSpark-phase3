<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Filament\Resources\SoftwareResource;
use App\Models\Software;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class CreateSoftware extends CreateRecord
{
    protected static string $resource = SoftwareResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['author_id'] = Auth::user()->id;
        $data['post_date'] = Carbon::now();
        $data['post_modified'] = Carbon::now();
        return $data;
    }

    protected function handleRecordCreation(array $data): Model
    {
        $record =  parent::handleRecordCreation($data);

        //handle tags
        if (isset($data['tags'])) {
            $thatEvent = Software::find($record->id);
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
        return 'Software created successfully';
    }
}
