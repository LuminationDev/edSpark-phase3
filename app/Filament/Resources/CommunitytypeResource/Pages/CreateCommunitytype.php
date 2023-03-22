<?php

namespace App\Filament\Resources\CommunitytypeResource\Pages;

use App\Filament\Resources\CommunitytypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateCommunitytype extends CreateRecord
{
    protected static string $resource = CommunitytypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Community type created successfully';
    }
}
