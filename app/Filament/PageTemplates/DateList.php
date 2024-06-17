<?php

namespace App\Filament\PageTemplates;

use AmidEsfahani\FilamentTinyEditor\TinyEditor;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
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
                    ->profile('edspark')
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
