<?php

namespace Powerpanel\MessagingSystem\Providers;

use Illuminate\Support\ServiceProvider;

class MessagingSystemServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'messagingsystem');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/messagingsystem'),
        ], 'messagingsystem-js');
       
         $this->publishes([
            __DIR__.'/../Resources/assets/css' => public_path('resources/css/packages/messagingsystem'),
        ], 'messagingsystem-css');
         
          $this->publishes([
            __DIR__.'/../Resources/assets/images' => public_path('resources/image/packages/messagingsystem'),
        ], 'messagingsystem-image');
          
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'messagingsystem-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'messagingsystem-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'messagingsystem');
    }

}
