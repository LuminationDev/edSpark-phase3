<?php

namespace App\Filament\Resources\SchoolmoderationResource\Pages;

use App\Filament\Resources\SchoolmoderationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchoolmoderations extends ListRecords
{
    protected static string $resource = SchoolmoderationResource::class;

    protected function getHeaderActions(): array
    {
        return [
        ];
    }
}
