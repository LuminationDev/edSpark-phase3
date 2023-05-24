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
                TextInput::make('Screen Size'),
                TextInput::make('Operating System'),
                TextInput::make('Processor'),
                TextInput::make('Memory capacity'),
                TextInput::make('Storage capacity'),
                TextInput::make('Graphics'),
                TextInput::make('Feature highlights (separate each feature with a comma)'),

            ])
                ->label('Tech Specs')
                ->collapsible()
        ];
    }
}
