<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {

        // Example to register custom navigation in admin panel
        // Filament::serving(function () {
        //     // First we  register a custom navigation group
        //     Filament::registerNavigationGroups(
        //         [
        //             NavigationGroup::make()
        //                 ->label('Shop')
        //                 ->icon('heroicon-s-shopping-cart')
        //                 ->collapsed(),
        //         ]
        //     );

        //     // Then we register the links that will go into that naviagtion group
        //     Filament::registerNavigationItems(
        //         [
        //             NavigationItem::make('My Custom Link 1')
        //                 ->url('https://filament.pirsch.io', shouldOpenInNewTab: true)
        //                 ->icon('heroicon-o-link')
        //                 ->group('Shop')
        //                 ->sort(1),
        //             NavigationItem::make('My Custom Link 2')
        //                 ->url('https://filament.pirsch.io', shouldOpenInNewTab: true)
        //                 ->icon('heroicon-o-link')
        //                 ->group('Shop')
        //                 ->sort(2),

        //         ]
        //     );
        // });
    }
}
