<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Unique views', '192.1k')
                ->description('32k increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                 ->chart([7, 2, 10, 3, 15, 4, 17])
                ->color('success'),
            Card::make('Bounce rate', '21%')
                ->description('7% decrease')
                ->descriptionIcon('heroicon-m-arrow-trending-down')
                ->color('danger'),
            Card::make('Average time on page', '3:12')
                ->description('3% increase')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('success'),
             Card::make('Processed', '192.1k')
                 ->color('success')
                 ->extraAttributes([
                     'class' => 'cursor-pointer',
                     'wire:click' => '$emitUp("setStatusFilter", "processed")',
                 ]),
        ];
    }
}
