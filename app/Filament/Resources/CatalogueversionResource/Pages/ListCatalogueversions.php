<?php

namespace App\Filament\Resources\CatalogueversionResource\Pages;

use App\Filament\Imports\CatalogueImporter;
use App\Filament\Resources\CatalogueversionResource;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

class ListCatalogueversions extends ListRecords
{
    protected static string $resource = CatalogueversionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
                ->importer(CatalogueImporter::class)
                ->label('New catalogue'),
        ];
    }
}
