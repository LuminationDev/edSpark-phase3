<?php

namespace App\Filament\Resources\EventtypeResource\Pages;

use App\Filament\Resources\EventtypeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEventtype extends CreateRecord
{
    protected static string $resource = EventtypeResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->previousUrl ?? $this->getResource()::getUrl('index');
    }

    protected function getCreatedNotificationTitle(): ?string
    {
        return 'Event type created successfully';
    }
}
