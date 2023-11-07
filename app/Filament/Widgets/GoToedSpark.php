<?php

namespace App\Filament\Widgets;

use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\Widget;

class GoToedSpark extends Widget
{
    protected static ?int $sort = -4;
    protected string $heading = 'Go back to edSpark';
    protected static string $view = 'filament.widgets.go-toed-spark';
}
