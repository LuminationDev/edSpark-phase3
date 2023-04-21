<?php

namespace App\Filament\Resources\SoftwareResource\Pages;

use App\Filament\Resources\SoftwareResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateSoftware extends CreateRecord
{
    protected static string $resource = SoftwareResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
//         dd($data); // before mutation
        $data['author_id'] = Auth::user()->id;
        $data['post_date'] = Carbon::now();
        $data['post_modified'] = Carbon::now();
        // dd($data); //after mutation
        return $data;
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
