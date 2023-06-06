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


final class HardwareAudioVisualTechSpecs
{
    public static function title()
    {
        return 'Audio Visual Tech Specs';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')->schema([
                TextInput::make('screen')->label('Screen Size'),
                TextInput::make('number_student')->label('Number of Students'),
                TextInput::make('features')->label('Feature highlights (separate each feature with a comma)'),

            ])
                ->label('Tech Specs')
                ->collapsible()
        ];
    }
}
