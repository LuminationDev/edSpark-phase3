<?php

namespace App\Filament\Resources\EventmoderationResource\Pages;

use App\Filament\Resources\EventmoderationResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;

class CreateEventmoderation extends CreateRecord
{
    protected static string $resource = EventmoderationResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return parent::mutateFormDataBeforeCreate($data); // TODO: Change the autogenerated stub
    }

    protected function handleRecordCreation(array $data): Model
    {
        return parent::handleRecordCreation($data); // TODO: Change the autogenerated stub
    }
}
