<?php

namespace Powerpanel\Alerts\Providers;

use Illuminate\Support\ServiceProvider;

class AlertsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."alerts";
        $packagePath = __DIR__.'/../Resources/views';
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'alerts');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__ . '/../routes.php';

        $this->publishes([
            __DIR__ . '/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/alerts'),
        ], 'alerts-js');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'alerts-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'alerts-seeders');

        $this->handleTranslations();
    }

    private function handleTranslations()
    {

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'alerts');
    }

}
