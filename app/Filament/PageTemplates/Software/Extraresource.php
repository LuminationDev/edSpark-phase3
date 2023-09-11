<?php

namespace App\Filament\PageTemplates\Software;

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

final class Extraresource
{
    public static function title()
    {
        return 'Extra Resources';
    }

    public static function schema()
    {
        return [
            Forms\Components\TextInput::make('resource_title')
                ->label('Resource title')
                ->maxLength(255),
            Repeater::make('item')->schema([
                TextInput::make('heading'),
                RichEditor::make('content')
            ])
                ->label('Item')
                ->collapsible()
        ];
    }
}
