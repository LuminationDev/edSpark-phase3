<?php

namespace App\Filament\atemp\Hardware;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;


final class HardwareEmergingTechTechSpecs
{
    public static function title()
    {
        return 'Emerging technology tech specs';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')->schema([
                TextInput::make('units')->label('Number of units included'),
                Select::make("tech_area")->label('Technology area(s)')
                    ->multiple()
                    ->options([
                        '3D Printing' =>'3D Printing',
                        'Microsoft Teams' => 'Microsoft Teams',
                        'Apple' => 'Apple',
                        'Frog' => 'Frog',
                        'IoT' => 'Internet of Things (IoT)',
                        'VR' => 'Virtual Reality',
                        'AR' => 'Augmented Reality',
                        'Robotics' => 'Robotics',
                ]),
                TextInput::make('features')->label('Feature highlights (separate each feature with a comma)'),

            ])
                ->label('Tech specs')
                ->collapsible()
        ];
    }
}
