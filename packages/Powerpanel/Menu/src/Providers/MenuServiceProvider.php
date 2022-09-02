<?php

namespace Powerpanel\Menu\Providers;

use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'menu');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'menu');
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'menu-migration');
        $this->publishes([__DIR__ . '/../database/seeders' => database_path('seeders')], 'menu-seeders');
        $this->publishes([__DIR__ . '/../Helpers' => app_path('Helpers')], 'menu-helper');
        $this->publishes([__DIR__.'/../Resources/assets/js/powerpanel/scripts/' => public_path('resources/pages/scripts/packages/menu')], 'menu-js');
        $this->publishes([__DIR__.'/../Resources/assets/js/powerpanel/plugins/' => public_path('resources/global/plugins/')], 'menu-plugin');
    }

}
