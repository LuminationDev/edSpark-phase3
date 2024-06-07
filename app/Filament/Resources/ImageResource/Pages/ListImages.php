<?php

namespace App\Filament\Resources\ImageResource\Pages;

use App\Components\EdsparkImageLibraryPicker;
use App\Filament\Resources\ImageResource;
use Filament\Actions;
use Filament\Actions\Action;
use Filament\Resources\Pages\ListRecords;

class ListImages extends ListRecords
{
    protected static string $resource = ImageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('upload')
                ->label(__('filament-image-library::translations.actions.upload'))
                ->modalSubmitActionLabel(__('filament-image-library::translations.actions.close_upload_action'))
                ->icon('heroicon-o-arrow-up-tray')
                ->form([
                    EdsparkImageLibraryPicker::make('image_id')
                        ->label(__('filament-image-library::translations.form.labels.image_picker.multiple.upload_only'))
                        ->multiple()
                        ->disableImageDeselect()
                        ->disableExisting()
                        ->enablePackageConversionDefinitions(),
                ]),
        ];
    }
}
