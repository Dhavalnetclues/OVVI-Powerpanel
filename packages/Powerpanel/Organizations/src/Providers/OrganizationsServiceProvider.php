<?php

namespace Powerpanel\Organizations\Providers;

use Illuminate\Support\ServiceProvider;

class OrganizationsServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."organizations";
				$packagePath = __DIR__.'/../Resources/views';
				$this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'organizations');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/organizations'),
        ], 'organizations-js');
        
        $this->publishes([
            __DIR__.'/../Resources/assets/css/frontview' => public_path('assets/css/packages/organizations'),
        ], 'organizations-css');


        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'organizations-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'organizations-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'organizations');
    }

}
