<?php

namespace Powerpanel\PageTemplates\Providers;

use Illuminate\Support\ServiceProvider;

class PageTemplatesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'pagetemplates');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/pagetemplates'),
        ], 'pagetemplates-js');
       
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'pagetemplates-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'pagetemplates-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'pagetemplates');
    }

}
