<?php

namespace App\Filament\Resources\HardwareResource\Pages;

use App\Filament\Resources\HardwareResource;
use App\Models\Hardware;
use Carbon\Carbon;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditHardware extends EditRecord
{
    protected static string $resource = HardwareResource::class;

    protected function mutateFormDatabeforeFill(array $data): array
    {
        $record = parent::getRecord();
        $targetData = Hardware::find($record->id);
        $data['tags'] = $targetData->tags;

        if (isset($data['tags'])) {
            $tagNames = $data['tags']->pluck('name');
            $data['tags'] = $tagNames;
        }
        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $record = parent::getRecord();
        $targetData = Hardware::find($record->id);
        if(isset($data['tags'])){
            $targetData->syncTags($data['tags']);
        }
        $data['modified'] = Carbon::now();

        return $data;
    }
    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
