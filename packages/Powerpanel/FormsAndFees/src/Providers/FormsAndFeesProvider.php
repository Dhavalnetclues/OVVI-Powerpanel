<?php

namespace Powerpanel\FormsAndFees\Providers;

use Illuminate\Support\ServiceProvider;

class FormsAndFeesProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."forms-and-fees";
        $packagePath = __DIR__.'/../Resources/views';
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'forms-and-fees');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/forms-and-fees'),
        ], 'forms-and-fees-js');

        $this->publishes([
            __DIR__.'/../Resources/assets/js/frontview' => public_path('assets/js/packages/forms-and-fees'),
        ], 'forms-and-fees-front-js');
        
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'forms-and-fees-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'forms-and-fees-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'forms-and-fees');
    }

}
