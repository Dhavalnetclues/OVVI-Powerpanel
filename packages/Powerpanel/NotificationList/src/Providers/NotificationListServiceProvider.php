<?php

namespace Powerpanel\NotificationList\Providers;

use Illuminate\Support\ServiceProvider;

class NotificationListServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'notificationlist');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/notificationlist'),
        ], 'notificationlist-js');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'notificationlist-migration');

        $this->publishes([
            __DIR__ . '/../database/seeds' => database_path('seeders'),
        ], 'notificationlist-seeds');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'notificationlist');
    }

}
