<?php

namespace App\Filament\Resources\SiteResource\Pages;

use App\Filament\Resources\SiteResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

use Konnco\FilamentImport\Actions\ImportAction;
use Konnco\FilamentImport\Actions\ImportField;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Filament\Forms\Components\Select;

class ListSites extends ListRecords
{
    protected static string $resource = SiteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),

            ImportAction::make()
                ->fields([
                    ImportField::make('site_id')
                        ->label('Site ID'),
                    ImportField::make('site_name')
                        ->label('Site Name'),
                    ImportField::make('site_value')
                        ->label('Site Value'),
                    ImportField::make('category_code')
                        ->label('Category Code'),
                    ImportField::make('category_desc')
                        ->label('Category Description'),
                    ImportField::make('site_type_code')
                        ->label('Site Type Code'),
                    ImportField::make('site_type_desc')
                        ->label('Site Type Description'),
                    ImportField::make('site_sub_type_code')
                        ->label('Site Sub Type Code'),
                    ImportField::make('site_sub_type_desc')
                        ->label('Site Sub Type Description'),
                    ImportField::make('site_latitude')
                        ->label('Site Latitude'),
                    ImportField::make('site_longitude')
                        ->label('Site Longitude')
                ])
        ];
    }
}
