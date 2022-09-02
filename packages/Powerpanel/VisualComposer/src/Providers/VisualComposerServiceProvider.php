<?php

namespace Powerpanel\VisualComposer\Providers;

use Illuminate\Support\ServiceProvider;

class VisualComposerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $frontPackageThemePath = \config('theme.front_package_path')."visualcomposer";
				$packagePath = __DIR__.'/../Resources/views';
				$this->loadViewsFrom([$frontPackageThemePath,$packagePath], 'visualcomposer');
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
            __DIR__.'/../Resources/assets/js' => public_path('resources/pages/scripts/packages/visualcomposer'),], 'powerpanel-visualcomposer-js');

        $this->publishes([
            __DIR__.'/../Resources/assets/css' => public_path('resources/css/packages/visualcomposer'),
        ], 'powerpanel-visualcomposer-css');

        $this->publishes([
            __DIR__.'/../Resources/assets/images' => public_path('assets/images/packages/visualcomposer'),
        ], 'powerpanel-visualcomposer-img');


        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'visualcomposer-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'visualcomposer-seeders');
        
         $this->handleTranslations();
    }
    
     private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'visualcomposer');
    }

}
