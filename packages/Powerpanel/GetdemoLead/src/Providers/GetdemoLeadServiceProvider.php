<?php

namespace Powerpanel\GetdemoLead\Providers;

use Illuminate\Support\ServiceProvider;

class GetdemoLeadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'getdemolead');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/../routes.php';

        // $this->publishes([
        //     __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/getdemoklead'),
        // ], 'getdemoklead-js');

        // $this->publishes([
        //     __DIR__ . '/../database/migrations' => database_path('migrations'),
        // ], 'getdemoklead-migration');

        // $this->publishes([
        //     __DIR__ . '/../database/seeders' => database_path('seeders'),
        // ], 'getdemoklead-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'getdemoklead');
    }

}
