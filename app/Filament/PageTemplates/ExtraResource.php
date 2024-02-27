<?php

namespace App\Filament\PageTemplates;

use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Repeater;
use Mohamedsabil83\FilamentFormsTinyeditor\Components\TinyEditor;

final class ExtraResource
{
    private static $backgroundColorTheme = [
        'yellow' => [
            'light' => '#FFF1CB',
            'med' => '#FFC836',
            'dark' => '#965A00',
        ],
        'red' => [
            'light' => '#FECCD0',
            'med' => '#DE4668',
            'dark' => '#9B2749',
        ],
        'orange' => [
            'light' => '#FFDECF',
            'med' => '#FF8D78',
            'dark' => '#A43D18',
        ],
        'purple' => [
            'light' => '#DBCCF5',
            'med' => '#8866C5',
            'dark' => '#6D479B',
        ],
        'blue' => [
            'light' => '#AEDCF3',
            'med' => '#0072DA',
            'dark' => '#03369A',
        ],
        'teal' => [
            'light' => '#B2F5EA',
            'med' => '#319795',
            'dark' => '#185E69',
        ],
        'navy' => [
            'light' => '#6e99ce', //was #3d6ba3
            'med' => '#002858',
            'dark' => '#002858',
        ],
    ];

    private static function getColorLabel()
    {
        $colorLabels = [];
        foreach (self::$backgroundColorTheme as $color => $properties) {
            $colorLabels[$properties['dark']] = ucfirst($color) ;
        }
        return $colorLabels;
    }

    public static function title()
    {
        return 'Extra Resources';
    }


    public static function schema()
    {
        return [
            Forms\Components\TextInput::make('title')
                ->label('Resource title')
                ->maxLength(255),
//            Forms\Components\ColorPicker::make('color')->label('Background color'),
            Forms\Components\Select::make('back_color')
                ->label('Background color')
                ->options(self::getColorLabel())
            ,
            Repeater::make('item')->schema([
                TextInput::make('heading'),
                TinyEditor::make('content')
                    ->label('Content')->fileAttachmentsDisk('local')
                    ->fileAttachmentsVisibility('public')
                    ->fileAttachmentsDirectory('public/uploads/image')
                    ->required(),
            ])
                ->label('Item')
                ->collapsible()
        ];
    }
}


