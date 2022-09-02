<?php

namespace Powerpanel\DocumentReport\Providers;

use Illuminate\Support\ServiceProvider;

class DocumentReportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'documentreport');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/documentreport'),
        ], 'documentreport-js');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'documentreport-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'documentreport-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'documentreport');
    }

}
