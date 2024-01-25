<?php

namespace App\Filament\Resources\CatalogueResource\Pages;

use App\Filament\Imports\CatalogueImporter;
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
//            ExportAction::make()->exporter(CatalogueExporter::class)
//                ->formats([ExportFormat::Csv])
//                ->fileName(fn (Export $export): string => "catalogue-" . now()->format('dmY') . ".csv"),
            Action::make('Delete all')
                ->icon('heroicon-m-x-mark')
                ->color(Color::Rose)
                ->requiresConfirmation()
                ->action(function () {
                    Catalogue::deleteAll();
                }),

            ImportAction::make()
                ->importer(CatalogueImporter::class)
        ];
    }
}
