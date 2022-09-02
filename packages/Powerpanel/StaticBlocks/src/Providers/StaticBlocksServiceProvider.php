<?php

namespace Powerpanel\StaticBlocks\Providers;

use Illuminate\Support\ServiceProvider;

class StaticBlocksServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'static-blocks');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/static-blocks'),
        ], 'static-blocks-js');

        $this->publishes([
            __DIR__.'/../Resources/assets/js/frontview' => public_path('assets/js/packages/static-blocks'),
        ], 'static-blocks-front-js');
        
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'static-blocks-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'static-blocks-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'static-blocks');
    }

}
