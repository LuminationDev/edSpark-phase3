<?php

namespace App\Filament\Resources\CatalogueResource\Pages;

use App\Filament\Imports\CatalogueUpdater;
use App\Filament\Resources\CatalogueResource;
use App\Models\Catalogue;
use Filament\Actions\Action;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Colors\Color;


class ListCatalogues extends ListRecords
{
    protected static string $resource = CatalogueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('Delete all')
                ->icon('heroicon-m-x-mark')
                ->color(Color::Rose)
                ->requiresConfirmation()
                ->action(function () {
                    Catalogue::deleteAll();
                }),


            ImportAction::make()
                ->label('Bulk update')
                ->importer(CatalogueUpdater::class)
        ];
    }
}
