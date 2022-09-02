<?php

namespace Powerpanel\FormBuilder\Providers;

use Illuminate\Support\ServiceProvider;

class FormBuilderServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'formbuilder');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/formbuilder'),
        ], 'formbuilder-js');
        
        $this->publishes([
            __DIR__.'/../Resources/assets/formBuilder' => public_path('assets/formbuilder'),
        ], 'formbuilder-plugin-js');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'formbuilder-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'formbuilder-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'formbuilder');
    }

}
