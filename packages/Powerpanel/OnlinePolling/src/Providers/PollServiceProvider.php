<?php

namespace Powerpanel\OnlinePolling\Providers;

use Illuminate\Support\ServiceProvider;

class PollServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
//    public function register()
//    {
//    
//        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'polls');
//        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'polls');
//    }
//
//    /**
//     * Bootstrap services.
//     *
//     * @return void
//     */
//    public function boot()
//    {
//        $this->loadRoutesFrom(__DIR__.'/../routes.php');
//        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'poll-migration');
//        $this->publishes([__DIR__ . '/../database/seeders' => database_path('seeders')], 'poll-seeders');
//        $this->publishes([__DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/poll')], 'poll-js');        
//    } 
//    
    
    
    
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."polls";
				$packagePath = __DIR__.'/../Resources/views';
				$this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'polls');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        
        include __DIR__.'/../routes.php';
//        echo 'hi';exit;
        $this->publishes([
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/poll'),
        ], 'poll-js');

        $this->publishes([
            __DIR__.'/../Resources/assets/js/frontview' => public_path('assets/js/packages/online-polling'),
        ], 'online-polling-front-js');
        
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'poll-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'poll-migration');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'polls');
    }
    

}

