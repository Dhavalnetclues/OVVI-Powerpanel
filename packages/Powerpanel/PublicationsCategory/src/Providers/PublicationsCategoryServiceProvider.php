<?php

namespace Powerpanel\PublicationsCategory\Providers;

use Illuminate\Support\ServiceProvider;

class PublicationsCategoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."publications-category";
				$packagePath = __DIR__.'/../Resources/views';
				$this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'publications-category');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/publicationscategory'),
        ], 'publicationscategory-js');


        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'publicationscategory-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'publicationscategory-seeders');
        
        $this->handleTranslations();
    }
    
     private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'publications-category');
    }

}
