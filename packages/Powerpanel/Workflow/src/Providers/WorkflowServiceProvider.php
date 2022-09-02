<?php

namespace Powerpanel\Workflow\Providers;

use Illuminate\Support\ServiceProvider;

class WorkflowServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->loadViewsFrom(__DIR__.'/../Resources/views', 'workflow');
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
            __DIR__.'/../Resources/assets/js/powerpanel' => public_path('resources/pages/scripts/packages/workflow'),
        ], 'workflow-js');
        
        $this->publishes([
            __DIR__.'/../Resources/assets/js/powerpanel/approval' => public_path('resources/pages/scripts'),
        ], 'workflow-approval-js');

         $this->publishes([
            __DIR__.'/../Resources/assets/css' => public_path('resources/css/packages/workflow'),
        ], 'workflow-css');
         
          $this->publishes([
            __DIR__.'/../Resources/assets/image' => public_path('resources/image/packages/workflow'),
        ], 'workflow-image');
         
          $this->publishes([
            __DIR__.'/../Resources/views/powerpanel/commentpopup' => resource_path('views/powerpanel/partials'),
        ], 'workflow-commentpopup');

        $this->publishes([
            __DIR__ . '/../database/migrations' => database_path('migrations'),
        ], 'workflow-migration');

        $this->publishes([
            __DIR__ . '/../database/seeders' => database_path('seeders'),
        ], 'workflow-seeders');
        
        $this->handleTranslations();
    }
    
    private function handleTranslations() {
        $this->loadTranslationsFrom(__DIR__.'/../Resources/lang', 'workflow');
    }

}
