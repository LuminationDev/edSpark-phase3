<?php

namespace App\Filament\Resources\PartnermoderationResource\Pages;

use App\Filament\Resources\PartnermoderationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPartnermoderation extends EditRecord
{
    protected static string $resource = PartnermoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
