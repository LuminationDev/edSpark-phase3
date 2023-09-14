<?php
namespace App\Filament\atemp\Partner;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

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
