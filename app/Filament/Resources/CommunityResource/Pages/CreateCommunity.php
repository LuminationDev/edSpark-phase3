<?php

namespace App\Filament\Resources\CommunityResource\Pages;

use App\Filament\Resources\CommunityResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class CreateCommunity extends CreateRecord
{
    protected static string $resource = CommunityResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // dd($data);
        $data['author_id'] = Auth::user()->id;
        $data['created_at'] = Carbon::now();
        $data['modified_at'] = Carbon::now();

        return $data;
    }

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Community created successfully';
    }
}
