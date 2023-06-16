<?php

namespace App\Filament\PageTemplates\Event;

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

final class dateItems
{
    public static function title()
    {
        return 'Date Template';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')->schema([
                TextInput::make('heading'),
                RichEditor::make('content'),
//                Forms\Components\Grid::make(2)
//                    ->schema([
                        Forms\Components\DateTimePicker::make('start_date')
                            ->required(),
//                        Forms\Components\DateTimePicker::make('end_date')
//                            ->required()
//                    ]),
            ])
            ->label('Item')
            ->collapsible()
        ];
    }
}
