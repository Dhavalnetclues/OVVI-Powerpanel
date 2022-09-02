<?php

namespace Powerpanel\BoardOfDirectors\Providers;

use Illuminate\Support\ServiceProvider;

class BoardOfDirectorsProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."boardofdirectors";
        $packagePath = __DIR__.'/../Resources/views';
        
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'boardofdirectors');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/boardofdirectors'),
        ], 'boardofdirectors-js');

        $this->publishes([
            __DIR__.'/../Resources/assets/js/frontview' => public_path('assets/js/packages/boardofdirectors'),
        ], 'boardofdirectors-front-js');
        
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'boardofdirectors-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'boardofdirectors-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'boardofdirectors');
    }

}
