<?php

namespace App\Filament\Imports;

use App\Models\Catalogue;
use Filament\Actions\Imports\ImportColumn;
use Filament\Actions\Imports\Importer;
use Filament\Actions\Imports\Models\Import;

class CatalogueImporter extends Importer
{
    protected static ?string $model = Catalogue::class;

    public static function getColumns(): array
    {
        return [
            ImportColumn::make('unique_reference')
                ->label('Unique Reference'),
                    ImportColumn::make('type')
                        ->label('Type'),
                    ImportColumn::make('brand')
                        ->label('Brand'),
                    ImportColumn::make('name')
                        ->label('Name'),
                    ImportColumn::make('vendor')
                        ->label('Vendor'),
                    ImportColumn::make('category')
                        ->label('Category'),
                    ImportColumn::make('price_inc_gst')
                        ->label('Price Including GST'),
                    ImportColumn::make('processor')
                        ->label('Processor'),
                    ImportColumn::make('storage')
                        ->label('Storage'),
                    ImportColumn::make('memory')
                        ->label('Memory'),
                    ImportColumn::make('form_factor')
                        ->label('Form Factor'),
                    ImportColumn::make('display')
                        ->label('Display'),
                    ImportColumn::make('graphics')
                        ->label('Graphics'),
                    ImportColumn::make('wireless')
                        ->label('Wireless'),
                    ImportColumn::make('webcam')
                        ->label('Webcam'),
                    ImportColumn::make('operating_system')
                        ->label('Operating System'),
                    ImportColumn::make('warranty')
                        ->label('Warranty'),
                    ImportColumn::make('battery_life')
                        ->label('Battery Life'),
                    ImportColumn::make('weight')
                        ->label('Weight'),
                    ImportColumn::make('stylus')
                        ->label('Stylus'),
                    ImportColumn::make('other')
                        ->label('Other'),
                    ImportColumn::make('available_now')
                        ->label('Available Now'),
                    ImportColumn::make('corporate')
                        ->label('Corporate'),
                    ImportColumn::make('administration')
                        ->label('Administration'),
                    ImportColumn::make('curriculum')
                        ->label('Curriculum'),
                    ImportColumn::make('image')
                        ->label('Image'),
                    ImportColumn::make('product_number')
                        ->label('Product Number'),
                    ImportColumn::make('price_expiry')
                        ->label('Price Expiry'),
            ];

    }

    public function resolveRecord(): ?Catalogue
    {
        // return Catalogue::firstOrNew([
        //     // Update existing records, matching them by `$this->data['column_name']`
        //     'email' => $this->data['email'],
        // ]);

        return new Catalogue();
    }

    public static function getCompletedNotificationBody(Import $import): string
    {
        $body = 'Your catalogue import has completed and ' . number_format($import->successful_rows) . ' ' . str('row')->plural($import->successful_rows) . ' imported.';

        if ($failedRowsCount = $import->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to import.';
        }

        return $body;
    }
}
