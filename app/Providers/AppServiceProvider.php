<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Navigation\UserMenuItem;

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
        // Filament::registerSvgIconDirectory('edspark-icon-pack', resource_path('svg/edspark-icon-pack')); //not working
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(): void
    {
	if($this->app->environment('production')) {
		\URL::forceScheme('https');
	}

        Filament::serving(function () {
            // Using vite
//            Filament::registerViteTheme('resources/css/filament.css');
            Filament::registerNavigationGroups([
                'Moderation',
                'Content Management',
                'Product Management',
                'School Management',
                'System',
            ]);
        });

        // Example to register custom navigation in admin panel
        // Filament::serving(function () {
        //     // First we  register a custom navigation group
        //     Filament::registerNavigationGroups(
        //         [
        //             NavigationGroup::make()
        //                 ->label('Moderation')
        //                 ->collapsed(),
        //         ]
        //     );

        //     // Then we register the links that will go into that naviagtion group
        //     Filament::registerNavigationItems(
        //         [
        //             NavigationItem::make('Advice')
        //                 // ->url('https://filament.pirsch.io', shouldOpenInNewTab: false)
        //                 ->icon('heroicon-o-collection')
        //                 ->group('Moderation')
        //                 ->sort(1),
        //             NavigationItem::make('Software')
        //                 // ->url('https://filament.pirsch.io', shouldOpenInNewTab: false)
        //                 ->icon('heroicon-o-collection')
        //                 ->group('Moderation')
        //                 ->sort(2),
        //             NavigationItem::make('Event')
        //                 // ->url('https://filament.pirsch.io', shouldOpenInNewTab: false)
        //                 ->icon('heroicon-o-collection')
        //                 ->group('Moderation')
        //                 ->sort(3),
        //             NavigationItem::make('Community')
        //                 // ->url('https://filament.pirsch.io', shouldOpenInNewTab: false)
        //                 ->icon('heroicon-o-collection')
        //                 ->group('Moderation')
        //                 ->sort(4),

        //         ]
        //     );
        // });

        //TODO custom icon pack
        // Filament::registerSvgIconPacks([
        //     'my-custom-icons' => [
        //         'path' => resource_path('svg'),
        //         'prefix' => 'my-',
        //     ],
        // ]);
    }
}
