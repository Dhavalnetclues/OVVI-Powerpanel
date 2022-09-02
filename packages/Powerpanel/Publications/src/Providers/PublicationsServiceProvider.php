<?php

namespace Powerpanel\Publications\Providers;

use Illuminate\Support\ServiceProvider;

class PublicationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."publications";
				$packagePath = __DIR__.'/../Resources/views';
				$this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'publications');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/publications'),
        ], 'publications-js');


        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'publications-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'publications-seeders');
        
        $this->handleTranslations();
    }
    
     private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'publications');
    }

}
