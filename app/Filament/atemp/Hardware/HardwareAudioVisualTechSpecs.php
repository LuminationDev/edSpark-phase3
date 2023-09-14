<?php

namespace App\Filament\atemp\Hardware;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;


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
