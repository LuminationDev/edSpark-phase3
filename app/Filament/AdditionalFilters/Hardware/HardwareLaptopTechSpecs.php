<?php

namespace App\Filament\AdditionalFilters\Hardware;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;


final class HardwareLaptopTechSpecs
{
    public static function title()
    {
        return 'Laptop tech specs';
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
                ->label('Tech specs')
                ->collapsible()
        ];
    }
}
