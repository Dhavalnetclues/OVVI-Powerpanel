<?php

namespace Powerpanel\EventCategory\Providers;

use Illuminate\Support\ServiceProvider;

class EventCategoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."event-category";
        $packagePath = __DIR__.'/../Resources/views';
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'event-category');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/eventcategory'),
        ], 'eventcategory-js');


        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'eventcategory-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'eventcategory-seeders');
       
        $this->handleTranslations();
    }

    private function handleTranslations() {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'eventcategory');
    }
}
