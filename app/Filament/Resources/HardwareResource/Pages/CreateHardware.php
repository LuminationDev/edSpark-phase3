<?php

namespace App\Filament\Resources\HardwareResource\Pages;

use App\Filament\Resources\HardwareResource;
use App\Models\Hardware;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateHardware extends CreateRecord
{
    protected static string $resource = HardwareResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['owner_id'] = Auth::user()->id;
        $data['created'] = Carbon::now();
        $data['modified'] = Carbon::now();
        $data['product_isLoan'] = false;
        $data['product_SKU'] = '100';

        return $data;

    }

    protected function handleRecordCreation(array $data): Model
    {
        $record =  parent::handleRecordCreation($data);

        //handle tags
        if (isset($data['tags'])) {
            $thatEvent = Hardware::find($record->id);
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
        return 'Hardware created successfully';
    }
}
