<?php

namespace App\Components;

use Filament\Forms\Components\Actions\Action;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
use Outerweb\FilamentImageLibrary\Filament\Forms\Components\ImageLibraryPicker;
use Outerweb\FilamentImageLibrary\Filament\Plugins\FilamentImageLibraryPlugin;
use Outerweb\ImageLibrary\Facades\ImageLibrary;

class EdsparkImageLibraryPicker extends ImageLibraryPicker
{
    protected function getUploadAction(): Action
    {
        return Action::make('upload')
            ->label(__('filament-image-library::translations.actions.upload'))
            ->modalSubmitActionLabel(__('filament-image-library::translations.actions.save'))
            ->icon('heroicon-o-arrow-up-tray')
            ->form([
                Select::make('disk')
                    ->required()
                    ->label(__('filament-image-library::translations.form.labels.disk'))
                    ->options(fn() => FilamentImageLibraryPlugin::get()->getAllowedDisks())
                    ->default(fn() => FilamentImageLibraryPlugin::get()->getDefaultDisk()),
                FileUpload::make('images')
                    ->label(
                        $this->getAllowsMultiple()
                            ? __('filament-image-library::translations.form.labels.upload_multiple')
                            : __('filament-image-library::translations.form.labels.upload')
                    )
                    ->required()
                    ->live()
                    ->multiple(fn() => $this->getAllowsMultiple())
                    ->saveUploadedFileUsing(function (TemporaryUploadedFile $file, Get $get): int {
                        $newFile = new UploadedFile(
                            $file->getRealPath(),
                            $file->getFilename(),
                            $file->getMimeType(),
                        );

                        return ImageLibrary::upload($newFile, $get('disk'),
                            [
                                'title' => strtolower($file->getClientOriginalName()),
                                'alt' => strtolower($file->getClientOriginalName()),
                            ])->id;
                    }),
            ])
            ->action(function (array $data, Set $set, Component $component): void {
                $imageIds = Arr::wrap($data['images'] ?? []);

                $set(
                    $component->getStatePath(false),
                    $this->getAllowsMultiple()
                        ? collect($component->getState())->concat($imageIds)->toArray()
                        : Arr::wrap($imageIds[0])
                );

                Notification::make()
                    ->success()
                    ->title(
                        count($imageIds) > 1
                            ? __('filament-image-library::translations.notifications.images_uploaded.title')
                            : __('filament-image-library::translations.notifications.image_uploaded.title')
                    )
                    ->send();
            })
            ->closeModalByClickingAway(false);
    }
}