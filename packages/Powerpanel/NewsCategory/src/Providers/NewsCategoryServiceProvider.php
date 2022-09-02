<?php

namespace Powerpanel\NewsCategory\Providers;

use Illuminate\Support\ServiceProvider;

class NewsCategoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."news-category";
        $packagePath = __DIR__.'/../Resources/views';
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'news-category');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/newscategory'),
        ], 'newscategory-js');


        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'newscategory-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'newscategory-seeders');
        
        $this->handleTranslations();
    }
    
     private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'news-category');
    }

}
