<?php

namespace App\Filament\Resources\TechnologyResource\Pages;

use App\Filament\Resources\TechnologyResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTechnology extends CreateRecord
{
    protected static string $resource = TechnologyResource::class;

    // protected function mutateFormDataBeforeCreate(array $data): array
    // {
    //     dd($data);
    //     // $data['author_id'] = Auth::user()->id;
    //     // $data['created_at'] = Carbon::now();
    //     // $data['modified_at'] = Carbon::now();

    //     // return $data;
    // }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Technology created successfully';
    }
}
