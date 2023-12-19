<?php

namespace App\Filament\PageTemplates;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Guava\FilamentIconPicker\Forms\IconPicker;
use Guava\FilamentIconPicker\Tables\IconColumn;

use Filament\Forms\Components\Builder;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\MarkdownEditor;
use Filament\Forms\Components\Select;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

final class DateList
{
    public static function title()
    {
        return 'Date and Time Template';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')->schema([
                TextInput::make('heading'),
                TinyEditor::make('content')
                    ->label('Content')->fileAttachmentsDisk('local')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('public/uploads/image')
                    ->required(),
                Forms\Components\DateTimePicker::make('start_date')
                    ->required(),
            ])
                ->label('Item')
                ->collapsible()
        ];
    }
}
