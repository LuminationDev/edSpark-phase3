<?php

namespace App\Filament\PageTemplates\Hardware;

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


final class HardwareEmergingTechTechSpecs
{
    public static function title()
    {
        return 'Emerging Technology Tech Specs';
    }

    public static function schema()
    {
        return [
            Repeater::make('item')->schema([
                TextInput::make('Number of Units'),
                Select::make("Technology Areas")
                    ->multiple()
                    ->options([
                        '3d_printing' =>'3D Printing',
                        'microsoft_teams' => 'Microsoft Teams',
                        'apple' => 'Apple',
                        'frog' => 'Frog',
                        'iot' => 'Internet of Things (IoT)',
                        'vr' => 'Virtual Reality',
                        'ar' => 'Augmented Reality',
                        'robotics' => 'Robotics',
                ])
            ])
                ->label('Tech Specs')
                ->collapsible()
        ];
    }
}
