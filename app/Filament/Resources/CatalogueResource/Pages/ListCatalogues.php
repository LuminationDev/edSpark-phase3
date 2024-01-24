<?php

namespace App\Filament\Resources\CatalogueResource\Pages;

use App\Filament\Imports\CatalogueImporter;
use App\Filament\Resources\CatalogueResource;
use App\Models\Catalogue;
use Filament\Actions;
use Filament\Actions\ImportAction;
use Filament\Resources\Pages\ListRecords;

//use Konnco\FilamentImport\Actions\ImportAction;
//use Konnco\FilamentImport\Actions\ImportField;

class ListCatalogues extends ListRecords
{
    protected static string $resource = CatalogueResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ImportAction::make()
            ->importer(CatalogueImporter::class)
//            ImportAction::make()
//                ->fields([
//                    ImportField::make('unique_reference')
//                        ->label('Unique Reference'),
//                    ImportField::make('type')
//                        ->label('Type'),
//                    ImportField::make('brand')
//                        ->label('Brand'),
//                    ImportField::make('name')
//                        ->label('Name'),
//                    ImportField::make('vendor')
//                        ->label('Vendor'),
//                    ImportField::make('category')
//                        ->label('Category'),
//                    ImportField::make('price_inc_gst')
//                        ->label('Price Including GST'),
//                    ImportField::make('processor')
//                        ->label('Processor'),
//                    ImportField::make('storage')
//                        ->label('Storage'),
//                    ImportField::make('memory')
//                        ->label('Memory'),
//                    ImportField::make('form_factor')
//                        ->label('Form Factor'),
//                    ImportField::make('display')
//                        ->label('Display'),
//                    ImportField::make('graphics')
//                        ->label('Graphics'),
//                    ImportField::make('wireless')
//                        ->label('Wireless'),
//                    ImportField::make('webcam')
//                        ->label('Webcam'),
//                    ImportField::make('operating_system')
//                        ->label('Operating System'),
//                    ImportField::make('warranty')
//                        ->label('Warranty'),
//                    ImportField::make('battery_life')
//                        ->label('Battery Life'),
//                    ImportField::make('weight')
//                        ->label('Weight'),
//                    ImportField::make('stylus')
//                        ->label('Stylus'),
//                    ImportField::make('other')
//                        ->label('Other'),
//                    ImportField::make('available_now')
//                        ->label('Available Now'),
//                    ImportField::make('corporate')
//                        ->label('Corporate'),
//                    ImportField::make('administration')
//                        ->label('Administration'),
//                    ImportField::make('curriculum')
//                        ->label('Curriculum'),
//                    ImportField::make('image')
//                        ->label('Image'),
//                    ImportField::make('product_number')
//                        ->label('Product Number'),
//                    ImportField::make('price_expiry')
//                        ->label('Price Expiry'),
//                ]),

        ];
    }
}
