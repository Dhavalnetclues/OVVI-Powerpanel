<?php

namespace Powerpanel\ComplaintLead\Providers;

use Illuminate\Support\ServiceProvider;

class ComplaintLeadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."complaintlead";
        $packagePath = __DIR__.'/../Resources/views';
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'complaintlead');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/complaintlead'),
        ], 'complaintlead-js');

         $this->publishes([
            __DIR__.'/../Resources/assets/js/frontview' => public_path('assets/js/packages/complaintlead'),
        ], 'complaintlead-front-js');
         
        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'complaintlead-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'complaintlead-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'complaintlead');
    }

}
