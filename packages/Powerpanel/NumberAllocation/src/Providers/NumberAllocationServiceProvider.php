<?php

namespace Powerpanel\NumberAllocation\Providers;

use Illuminate\Support\ServiceProvider;

class NumberAllocationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."number-allocation";
				$packagePath = __DIR__.'/../Resources/views';
				$this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'number-allocation');

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'number-allocation');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/number-allocation'),
        ], 'number-allocation-js');


        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'number-allocation-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'number-allocation-seeders');

        $this->handleTranslations();
    }

    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'number-allocation');
    }

}
