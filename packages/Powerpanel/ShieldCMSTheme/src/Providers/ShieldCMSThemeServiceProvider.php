<?php

namespace Powerpanel\ShieldCMSTheme\Providers;

use Illuminate\Support\ServiceProvider;

class ShieldCMSThemeServiceProvider extends ServiceProvider {

    /**
     * Register services.
     *
     * @return void
     */
    public function register() {
        $this->loadViewsFrom(__DIR__ . '/../Resources/views', 'shiledcmstheme');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot() {
        include __DIR__ . '/../routes.php';

        
        $this->publishes([__DIR__ . '/../database/migrations' => database_path('migrations')], 'shiledcmstheme-migration');

        $this->publishes([__DIR__ . '/../database/seeders' => database_path('seeders')], 'shiledcmstheme-seeders');

        $this->publishes([__DIR__ . '/../Controllers/Auth' => app_path('Http/Controllers/Auth'),], 'powerpanel-shiledcmstheme-auth');

        $this->publishes([__DIR__ . '/../Middleware' => app_path('Http/Middleware'),], 'powerpanel-shiledcmstheme-middleware');
        
        $this->publishes([__DIR__ . '/../Traits' => app_path('Http/Traits'),], 'powerpanel-shiledcmstheme-traits');

        $this->publishes([__DIR__ . '/../Exports' => app_path('Exports'),], 'powerpanel-shiledcmstheme-exports');

        $this->publishes([__DIR__ . '/../Helpers' => app_path('Helpers'),], 'powerpanel-shiledcmstheme-helpers');
        
        $this->publishes([__DIR__ . '/../Kernel' => app_path('Http'),], 'powerpanel-shiledcmstheme-kernel');
        
        $this->publishes([__DIR__ . '/../Controllers/powerpanel/frontcontroller' => app_path('Http/Controllers'),], 'powerpanel-shiledcmstheme-frontcontroller');
        
        $this->publishes([__DIR__ . '/../Controllers/powerpanel/powerpanelcontroller' => app_path('Http/Controllers/Powerpanel'),], 'powerpanel-shiledcmstheme-powerpanelcontroller');

        $this->publishes([__DIR__ . '/../Models' => app_path(),], 'powerpanel-shiledcmstheme-models');
        
        $this->publishes([__DIR__ . '/../Resources/views/frontend' => resource_path('views'),], 'powerpanel-shiledcmstheme-frontend');
        
        $this->publishes([__DIR__ . '/../Resources/lang/en' => resource_path('lang/en'),], 'powerpanel-shiledcmstheme-template');
        
        $this->publishes([__DIR__ . '/../Resources/views/emails' => resource_path('views/emails'),], 'powerpanel-shiledcmstheme-emails');
        
        $this->publishes([__DIR__ . '/../Resources/views/errors' => resource_path('views/emails'),], 'powerpanel-shiledcmstheme-errors');

        $this->publishes([__DIR__ . '/../Resources/views/powerpanel/partials' => resource_path('views/powerpanel/partials')], 'powerpanel-shiledcmstheme-partials');

        $this->publishes([__DIR__ . '/../Resources/views/powerpanel/layouts' => resource_path('views/powerpanel/layouts')], 'powerpanel-shiledcmstheme-layouts');

        $this->publishes([__DIR__ . '/../Resources/views/powerpanel/media_manager' => resource_path('views/powerpanel/media_manager')], 'powerpanel-shiledcmstheme-media_manager');

        $this->publishes([__DIR__ . '/../Resources/views/powerpanel/mediamanagersidebar' => resource_path('views/powerpanel/mediamanagersidebar')], 'powerpanel-shiledcmstheme-mediamanagersidebar');

        $this->publishes([__DIR__ . '/../Resources/assets/front' => public_path('assets')], 'powerpanel-shiledcmstheme-libraries');

        $this->publishes([__DIR__ . '/../Resources/assets/resources' => public_path('resources'),], 'powerpanel-shiledcmstheme-resources');

        $this->publishes([__DIR__ . '/../Resources/assets/js' => public_path('resources/pages/scripts/packages')], 'powerpanel-shiledcmstheme-js');
        
        //$this->publishes([__DIR__ . '/../Resources/assets/images' => public_path('assets/images'),], 'powerpanel-shiledcmstheme-images');
        
        //$this->publishes([__DIR__ . '/../Resources/assets/plugin' => public_path('assets/global/plugins'),], 'powerpanel-shiledcmstheme-plugins');
        
        //$this->publishes([__DIR__ . '/../Resources/assets/libraries' => public_path('assets/libraries'),], 'powerpanel-shiledcmstheme-libraries');

        

        $this->handleTranslations();
    }

    private function handleTranslations() {

        $this->loadTranslationsFrom(__DIR__ . '/../Resources/lang', 'shiledcmstheme');
    }

}
