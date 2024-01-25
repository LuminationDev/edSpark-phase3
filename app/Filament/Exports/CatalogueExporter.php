<?php

namespace App\Filament\Exports;

use App\Models\Catalogue;
use Filament\Actions\Exports\ExportColumn;
use Filament\Actions\Exports\Exporter;
use Filament\Actions\Exports\Models\Export;

class CatalogueExporter extends Exporter
{
    protected static ?string $model = Catalogue::class;

    public static function getColumns(): array
    {
        return [
            ExportColumn::make('unique_reference')
                ->label('Unique Reference'),
            ExportColumn::make('type')
                ->label('Type'),
            ExportColumn::make('brand')
                ->label('Brand'),
            ExportColumn::make('name')
                ->label('Name'),
            ExportColumn::make('vendor')
                ->label('Vendor'),
            ExportColumn::make('category')
                ->label('Category'),
            ExportColumn::make('price_inc_gst')
                ->label('Price Including GST'),
            ExportColumn::make('processor')
                ->label('Processor'),
            ExportColumn::make('storage')
                ->label('Storage'),
            ExportColumn::make('memory')
                ->label('Memory'),
            ExportColumn::make('form_factor')
                ->label('Form Factor'),
            ExportColumn::make('display')
                ->label('Display'),
            ExportColumn::make('graphics')
                ->label('Graphics'),
            ExportColumn::make('wireless')
                ->label('Wireless'),
            ExportColumn::make('webcam')
                ->label('Webcam'),
            ExportColumn::make('operating_system')
                ->label('Operating System'),
            ExportColumn::make('warranty')
                ->label('Warranty'),
            ExportColumn::make('battery_life')
                ->label('Battery Life'),
            ExportColumn::make('weight')
                ->label('Weight'),
            ExportColumn::make('stylus')
                ->label('Stylus'),
            ExportColumn::make('other')
                ->label('Other'),
            ExportColumn::make('available_now')
                ->label('Available Now'),
            ExportColumn::make('corporate')
                ->label('Corporate'),
            ExportColumn::make('administration')
                ->label('Administration'),
            ExportColumn::make('curriculum')
                ->label('Curriculum'),
            ExportColumn::make('image')
                ->label('Image'),
            ExportColumn::make('product_number')
                ->label('Product Number'),
            ExportColumn::make('price_expiry')
                ->label('Price Expiry'),
        ];
    }

    public static function getCompletedNotificationBody(Export $export): string
    {
        $body = 'Your catalogue export has completed and ' . number_format($export->successful_rows) . ' ' . str('row')->plural($export->successful_rows) . ' exported.';

        if ($failedRowsCount = $export->getFailedRowsCount()) {
            $body .= ' ' . number_format($failedRowsCount) . ' ' . str('row')->plural($failedRowsCount) . ' failed to export.';
        }

        return $body;
    }

    public function tries(): int
    {
        return 5;
    }
}
