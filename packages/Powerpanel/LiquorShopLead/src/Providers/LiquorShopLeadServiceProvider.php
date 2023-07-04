<?php

namespace Powerpanel\LiquorShopLead\Providers;

use Illuminate\Support\ServiceProvider;

class LiquorShopLeadServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."liquorShopLead";
        $packagePath = __DIR__.'/../Resources/views';
        $this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'liquorShopLead');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/liquorshoplead'),
        ], 'liquorshoplead-js');

       
      
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'liquorshoplead');
    }

}
