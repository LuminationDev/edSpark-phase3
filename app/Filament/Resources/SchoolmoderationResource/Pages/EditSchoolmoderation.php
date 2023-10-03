<?php

namespace App\Filament\Resources\SchoolmoderationResource\Pages;

use App\Filament\Resources\SchoolmoderationResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSchoolmoderation extends EditRecord
{
    protected static string $resource = SchoolmoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
