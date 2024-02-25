<?php

namespace App\Filament\Resources\SchoolResource\Pages;

use App\Filament\Resources\SchoolResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListSchools extends ListRecords
{
    protected static string $resource = SchoolResource::class;
    protected ?string $subheading = 'Here you can find school profiles on edSpark. Use the filter function to see archived profiles';
    protected function getHeaderActions(): array
    {
        return [
             Actions\CreateAction::make(),
        ];
    }
}
