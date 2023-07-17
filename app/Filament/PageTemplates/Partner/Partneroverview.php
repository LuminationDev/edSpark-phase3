<?php
namespace App\Filament\PageTemplates\Partner;

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

final class Partneroverview
{
    public static function title()
    {
        return 'Partner Overview';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')
                ->schema([
                    TextInput::make('heading'),
                    RichEditor::make('content')
                ])->label('Item')
                ->collapsible()
        ];
    }
}
