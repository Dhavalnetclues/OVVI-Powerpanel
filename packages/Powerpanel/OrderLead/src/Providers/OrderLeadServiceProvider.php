<?php

namespace Powerpanel\OrderLead\Providers;

use Illuminate\Support\ServiceProvider;

class OrderLeadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'orderlead');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/orderlead'),
        ], 'orderlead-js');
      
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'formbuilderlead-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'formbuilderlead-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'orderlead');
    }

}
