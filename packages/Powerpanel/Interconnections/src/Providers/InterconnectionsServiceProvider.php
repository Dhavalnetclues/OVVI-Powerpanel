<?php

namespace Powerpanel\Interconnections\Providers;

use Illuminate\Support\ServiceProvider;

class InterconnectionsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."interconnections";
        $packagePath = __DIR__.'/../Resources/views';
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'interconnections');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/interconnections'),
        ], 'interconnections-js');
        
        $this->publishes([
            __DIR__.'/../Resources/assets/css/frontview' => public_path('assets/css/packages/interconnections'),
        ], 'interconnections-css');


        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'interconnections-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'interconnections-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'interconnections');
    }

}
