<?php

namespace Powerpanel\LiveUser\Providers;

use Illuminate\Support\ServiceProvider;

class LiveUserServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'liveuser');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/liveuser'),
        ], 'liveuser-js');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'liveuser-migration');

       $this->publishes([
           __DIR__ . '/../database/seeders' => database_path('seeders'),
       ], 'liveuser-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'liveuser');
    }

}
