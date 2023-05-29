<?php

namespace App\Filament\PageTemplates\Hardware;

use Faker\Provider\Text;
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


final class HardwareLaptopTechSpecs
{
    public static function title()
    {
        return 'Laptop Tech Specs';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')->schema([
                TextInput::make('screen')->label('Screen Size'),
                TextInput::make('os')->label('Operating System'),
                TextInput::make('processor')->label('Processor'),
                TextInput::make('memory')->label('Memory Capacity'),
                TextInput::make('storage')->label('Storage Capacity'),
                TextInput::make('graphic')->label('Graphics'),
                TextInput::make('features')->label('Features Highlights (separate with a comma)'),

            ])
                ->label('Tech Specs')
                ->collapsible()
        ];
    }
}
