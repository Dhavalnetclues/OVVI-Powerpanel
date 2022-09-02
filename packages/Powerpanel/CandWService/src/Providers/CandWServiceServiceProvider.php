<?php

namespace Powerpanel\CandWService\Providers;

use Illuminate\Support\ServiceProvider;

class CandWServiceServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."candwservice";
        $packagePath = __DIR__.'/../Resources/views';
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'candwservice');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/candwservice'),
        ], 'candwservice-js');

         $this->publishes([
            __DIR__.'/../Resources/assets/js/frontview' => public_path('assets/js/packages/candwservice'),
        ], 'candwservice-front-js');
         
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'candwservice-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'candwservice-seeders');
        
        $this->handleTranslations();
    }
    
     private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'candwservice');
    }

}
