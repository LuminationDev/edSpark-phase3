<?php
namespace App\Filament\PageTemplates;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\TextInput;

final class Faq
{
    public static function title()
    {
        return 'FAQ';
    }

    public static function schema()
    {
        return [
            TextInput::make('title'),
            Repeator::make('faq')->label('FAQ')->schema([
                TextInput::make('title'),
                Repeator::make('items')->schema([
                    TextInput::make('title'),
                    RichEditor::make('content')
                ])
            ])
        ];
    }
}
