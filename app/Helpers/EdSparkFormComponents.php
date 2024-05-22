<?php

namespace App\Helpers;

use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Str;
use Livewire\Features\SupportFileUploads\TemporaryUploadedFile;
class EdSparkFormComponents {
    public static function createFileUploadComponent(string $directory, string $prependFileName): FileUpload
    {
        return FileUpload::make('cover_image')
            ->label(new CustomHtmlable("Cover Image <span class='text-xs italic'> (500px * 500px / 1:1 aspect ratio) </span>"))
            ->validationAttribute('cover image')
            ->preserveFilenames()
            ->disk('azure')
            ->directory($directory)
            ->acceptedFileTypes(['image/jpeg', 'image/jpg', 'image/png'])
            ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file) use ($prependFileName): string {
                return (string)str($prependFileName . Str::random(30) . time() . '.' . $file->getClientOriginalExtension())->prepend('edSpark-event-');
            });
    }
}