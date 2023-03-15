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
        //                 ->label('User Management')
        //                 ->icon('heroicon-o-collection')
        //                 ->collapsed(),
        //         ]
        //     );

        //     // Then we register the links that will go into that naviagtion group
        //     Filament::registerNavigationItems(
        //         [
        //             NavigationItem::make('My Custom Link 1')
        //                 ->url('https://filament.pirsch.io', shouldOpenInNewTab: false)
        //                 ->icon('heroicon-o-link')
        //                 ->group('User Management')
        //                 ->sort(1),
        //             NavigationItem::make('My Custom Link 2')
        //                 ->url('https://filament.pirsch.io', shouldOpenInNewTab: false)
        //                 ->icon('heroicon-o-link')
        //                 ->group('User Management')
        //                 ->sort(2),

        //         ]
        //     );
        // });
    }
}
