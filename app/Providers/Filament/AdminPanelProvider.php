<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Outerweb\FilamentImageLibrary\Filament\Plugins\FilamentImageLibraryPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->font('Poppins')
            ->id('admin')
            ->path('admin')
            ->login()
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Orange,
                'primary' => [
                    50 => '229, 245, 245',
                    100 => '204, 235, 235',
                    200 => '153, 214, 214',
                    300 => '102, 184, 184',
                    400 => '77, 166, 166',
                    500 => '51, 153, 153', // Base color
                    600 => '41, 122, 122',
                    700 => '31, 92, 92',
                    800 => '20, 61, 61',
                    900 => '10, 31, 31',
                    950 => '5, 15, 15',
                ],
            ])
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->unsavedChangesAlerts()
            ->favicon(url(env('VITE_SERVER_IMAGE_API') . '/uploads/image/edsparkLogo.png'))
            ->brandLogo(url(env('VITE_SERVER_IMAGE_API') . '/uploads/image/edsparkLogo.png'))
            ->brandLogoHeight('4rem')
            ->plugins([
                FilamentImageLibraryPlugin::make()
                    ->addAllowedDisk('public')

            ]);
    }
}
