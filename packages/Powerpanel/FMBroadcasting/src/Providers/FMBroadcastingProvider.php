<?php

namespace Powerpanel\FMBroadcasting\Providers;

use Illuminate\Support\ServiceProvider;

class FMBroadcastingProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."fmbroadcasting";
        $packagePath = __DIR__.'/../Resources/views';
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'fmbroadcasting');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/../routes.php';

        $this->publishes([
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/fmbroadcasting'),
        ], 'fmbroadcasting-js');

         $this->publishes([
            __DIR__.'/../Resources/assets/js/frontview' => public_path('assets/js/packages/fmbroadcasting'),
        ], 'fmbroadcasting-front-js');
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'fmbroadcasting-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'fmbroadcasting-seeders');
        
        $this->handleTranslations();
    }
    
     private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'fmbroadcasting');
    }

}
