<?php

namespace Powerpanel\PopupContent\Providers;

use Illuminate\Support\ServiceProvider;

class PopupContentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'popup-content');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
//        include __DIR__ . '/../routes.php';
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->publishes([
            __DIR__ . '/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/popup-content'),
        ], 'popup-content-js');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'popup-content-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'popup-content-seeders');

        $this->handleTranslations();
    }

    private function handleTranslations()
    {

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'popup-content');
    }

}
