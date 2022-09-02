<?php

namespace Powerpanel\Companies\Providers;

use Illuminate\Support\ServiceProvider;

class CompaniesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'companies');
        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'companies');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__.'/../routes.php');
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'companies-migration');
        $this->publishes([__DIR__ . '/../database/seeders' => database_path('seeders')], 'companies-seeders');
        $this->publishes([__DIR__ . '/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/companies')], 'companies-js');
    }
    
}
