<?php

namespace App\Components;

use Filament\Actions\Action;
use Outerweb\FilamentImageLibrary\Filament\Pages\ImageLibrary;

class EdsparkImageLibrary extends ImageLibrary
{
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