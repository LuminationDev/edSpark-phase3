<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Container\Container;


class SvgPackServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {

        $this->app->singleton('edspark-svg', function () {
            return file_get_contents(resource_path('svg/edspark-icon-pack/appleIcon.svg'));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
