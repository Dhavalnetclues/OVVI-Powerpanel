<?php

return [
    /*
      |--------------------------------------------------------------------------
      | Application Name
      |--------------------------------------------------------------------------
      |
      | This value is the name of your application. This value is used when the
      | framework needs to place the application's name in a notification or
      | any other location as required by the application or its packages.
      |
     */

    'name' => env('APP_NAME', 'Laravel'),
    /*
      |--------------------------------------------------------------------------
      | Application Environment
      |--------------------------------------------------------------------------
      |
      | This value determines the "environment" your application is currently
      | running in. This may determine how you prefer to configure various
      | services your application utilizes. Set this in your ".env" file.
      |
     */
    'env' => env('APP_ENV', 'production'),
    /*
      |--------------------------------------------------------------------------
      | Application Debug Mode
      |--------------------------------------------------------------------------
      |
      | When your application is in debug mode, detailed error messages with
      | stack traces will be shown on every error that occurs within your
      | application. If disabled, a simple generic error page is shown.
      |
     */
    'debug' => env('APP_DEBUG', false),
    /*
      |--------------------------------------------------------------------------
      | Application URL
      |--------------------------------------------------------------------------
      |
      | This URL is used by the console to properly generate URLs when using
      | the Artisan command line tool. You should set this to the root of
      | your application so that it is used when running Artisan tasks.
      |
     */
    'url' => env('APP_URL', 'http://localhost'),
    /*
      |--------------------------------------------------------------------------
      | Application Timezone
      |--------------------------------------------------------------------------
      |
      | Here you may specify the default timezone for your application, which
      | will be used by the PHP date and date-time functions. We have gone
      | ahead and set this to a sensible default for you out of the box.
      |
     */
    'timezone' => 'UTC',
    /*
      |--------------------------------------------------------------------------
      | Application Locale Configuration
      |--------------------------------------------------------------------------
      |
      | The application locale determines the default locale that will be used
      | by the translation service provider. You are free to set this value
      | to any of the locales which will be supported by the application.
      |
     */
    'locale' => 'en',
    /*
      |--------------------------------------------------------------------------
      | Application Fallback Locale
      |--------------------------------------------------------------------------
      |
      | The fallback locale determines the locale to use when the current one
      | is not available. You may change the value to correspond to any of
      | the language folders that are provided through your application.
      |
     */
    'fallback_locale' => 'en',
    /*
      |--------------------------------------------------------------------------
      | Encryption Key
      |--------------------------------------------------------------------------
      |
      | This key is used by the Illuminate encrypter service and should be set
      | to a random, 32 character string, otherwise these encrypted strings
      | will not be safe. Please do this before deploying an application!
      |
     */
    'key' => env('APP_KEY'),
    'cipher' => 'AES-256-CBC',
    /*
      |--------------------------------------------------------------------------
      | Logging Configuration
      |--------------------------------------------------------------------------
      |
      | Here you may configure the log settings for your application. Out of
      | the box, Laravel uses the Monolog PHP logging library. This gives
      | you a variety of powerful log handlers / formatters to utilize.
      |
      | Available Settings: "single", "daily", "syslog", "errorlog"
      |
     */
    'log' => env('APP_LOG', 'single'),
    'log_level' => env('APP_LOG_LEVEL', 'debug'),
    /*
      |--------------------------------------------------------------------------
      | Autoloaded Service Providers
      |--------------------------------------------------------------------------
      |
      | The service providers listed here will be automatically loaded on the
      | request to your application. Feel free to add your own services to
      | this array to grant expanded functionality to your applications.
      |
     */
    'providers' => [
        /*
         * Laravel Framework Service Providers...
         */
        Illuminate\Auth\AuthServiceProvider::class,
        Illuminate\Broadcasting\BroadcastServiceProvider::class,
        Illuminate\Bus\BusServiceProvider::class,
        Illuminate\Cache\CacheServiceProvider::class,
        Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
        Illuminate\Cookie\CookieServiceProvider::class,
        Illuminate\Database\DatabaseServiceProvider::class,
        Illuminate\Encryption\EncryptionServiceProvider::class,
        Illuminate\Filesystem\FilesystemServiceProvider::class,
        Illuminate\Foundation\Providers\FoundationServiceProvider::class,
        Illuminate\Hashing\HashServiceProvider::class,
        Illuminate\Mail\MailServiceProvider::class,
        Illuminate\Notifications\NotificationServiceProvider::class,
        Illuminate\Pagination\PaginationServiceProvider::class,
        Illuminate\Pipeline\PipelineServiceProvider::class,
        Illuminate\Queue\QueueServiceProvider::class,
        Illuminate\Redis\RedisServiceProvider::class,
        Illuminate\Auth\Passwords\PasswordResetServiceProvider::class,
        Illuminate\Session\SessionServiceProvider::class,
        Illuminate\Translation\TranslationServiceProvider::class,
        Illuminate\Validation\ValidationServiceProvider::class,
        Illuminate\View\ViewServiceProvider::class,
        /*
         * Package Service Providers...
         */
        
        /*
         * Application Service Providers...
         */
        App\Providers\AppServiceProvider::class,
        App\Providers\AuthServiceProvider::class,
        // App\Providers\BroadcastServiceProvider::class,
        App\Providers\EventServiceProvider::class,
        App\Providers\RouteServiceProvider::class,
        App\Providers\MailConfigServiceProvider::class,
        Zizaco\Entrust\EntrustServiceProvider::class,
        Collective\Html\HtmlServiceProvider::class,
        Anhskohbo\NoCaptcha\NoCaptchaServiceProvider::class,
        Maatwebsite\Excel\ExcelServiceProvider::class,
        UniSharp\LaravelFilemanager\LaravelFilemanagerServiceProvider::class,
        Intervention\Image\ImageServiceProvider::class,
        Mariuzzo\LaravelJsLocalization\LaravelJsLocalizationServiceProvider::class,
        Barryvdh\Debugbar\ServiceProvider::class,
        Spatie\Sitemap\SitemapServiceProvider::class,
        //Spatie\Backup\BackupServiceProvider::class,
        ZanySoft\Zip\ZipServiceProvider::class,
        Cviebrock\EloquentSluggable\ServiceProvider::class,
        Aws\Laravel\AwsServiceProvider::class,
//        MaddHatter\LaravelFullcalendar\ServiceProvider::class,
        Jenssegers\Agent\AgentServiceProvider::class,
        Powerpanel\Alerts\Providers\AlertsServiceProvider::class,
        Powerpanel\Organizations\Providers\OrganizationsServiceProvider::class,
        Powerpanel\Department\Providers\DepartmentServiceProvider::class,
        Powerpanel\QuickLinks\Providers\QuickLinksServiceProvider::class,
        Powerpanel\LinksCategory\Providers\LinksCategoryServiceProvider::class,
        Powerpanel\Links\Providers\LinksServiceProvider::class,
        Powerpanel\FaqCategory\Providers\FaqCategoryServiceProvider::class,
        Powerpanel\Faq\Providers\FaqServiceProvider::class,
        Powerpanel\NewsCategory\Providers\NewsCategoryServiceProvider::class,
        Powerpanel\News\Providers\NewsServiceProvider::class,
        Powerpanel\EventCategory\Providers\EventCategoryServiceProvider::class,
        Powerpanel\Events\Providers\EventsServiceProvider::class,
        Powerpanel\PublicationsCategory\Providers\PublicationsCategoryServiceProvider::class,
        Powerpanel\Publications\Providers\PublicationsServiceProvider::class,
        Powerpanel\VisualComposer\Providers\VisualComposerServiceProvider::class,
        Powerpanel\BlogCategory\Providers\BlogCategoryServiceProvider::class,
        Powerpanel\Blogs\Providers\BlogsServiceProvider::class,
        Powerpanel\PhotoAlbum\Providers\PhotoAlbumServiceProvider::class,
        Powerpanel\PhotoGallery\Providers\PhotoGalleryServiceProvider::class,
        Powerpanel\Workflow\Providers\WorkflowServiceProvider::class,
        Powerpanel\RoleManager\Providers\RoleManagerServiceProvider::class,
        Powerpanel\Careers\Providers\CareersServiceProvider::class,
        Powerpanel\VideoGallery\Providers\VideoGalleryServiceProvider::class,
        Powerpanel\CmsPage\Providers\CmsPageServiceProvider::class,
        Powerpanel\Banner\Providers\BannerServiceProvider::class,
        Powerpanel\ContactInfo\Providers\ContactInfoServiceProvider::class,
        Powerpanel\ContactUsLead\Providers\ContactUsLeadServiceProvider::class,
        Powerpanel\GetdemoLead\Providers\GetdemoLeadServiceProvider::class,
        Powerpanel\ResellerLead\Providers\ResellerLeadServiceProvider::class,
        Powerpanel\OrderLead\Providers\OrderLeadServiceProvider::class,
        Powerpanel\ServicesCategory\Providers\ServicesCategoryServiceProvider::class,
        Powerpanel\Services\Providers\ServicesServiceProvider::class,
        Powerpanel\Testimonial\Providers\TestimonialServiceProvider::class,
        Powerpanel\Team\Providers\TeamServiceProvider::class,
        Powerpanel\NewsletterLead\Providers\NewsletterLeadServiceProvider::class,
        Powerpanel\SponsorCategory\Providers\SponsorCategoryServiceProvider::class,
        Powerpanel\Sponsors\Providers\SponsorsServiceProvider::class,
        Powerpanel\ShowCategory\Providers\ShowCategoryServiceProvider::class,
        Powerpanel\Show\Providers\ShowServiceProvider::class,
        Powerpanel\ProductCategory\Providers\ProductCategoryServiceProvider::class,
        Powerpanel\Products\Providers\ProductsServiceProvider::class,
        Powerpanel\Advertise\Providers\AdvertiseServiceProvider::class,
        Powerpanel\ProjectCategory\Providers\ProjectCategoryServiceProvider::class,
        Powerpanel\Projects\Providers\ProjectsServiceProvider::class,
        Powerpanel\ClientCategory\Providers\ClientCategoryServiceProvider::class,
        Powerpanel\Client\Providers\ClientServiceProvider::class,
         Powerpanel\BookAppointment\Providers\BookAppointmentServiceProvider::class,
    ],
    /*
      |--------------------------------------------------------------------------
      | Class Aliases
      |--------------------------------------------------------------------------
      |
      | This array of class aliases will be registered when this application
      | is started. However, feel free to register as many as you wish as
      | the aliases are "lazy" loaded so they don't hinder performance.
      |
     */
    'aliases' => [
        'App' => Illuminate\Support\Facades\App::class,
        'Artisan' => Illuminate\Support\Facades\Artisan::class,
        'Auth' => Illuminate\Support\Facades\Auth::class,
        'Blade' => Illuminate\Support\Facades\Blade::class,
        'Broadcast' => Illuminate\Support\Facades\Broadcast::class,
        'Bus' => Illuminate\Support\Facades\Bus::class,
        'Cache' => Illuminate\Support\Facades\Cache::class,
        'Config' => Illuminate\Support\Facades\Config::class,
        'Cookie' => Illuminate\Support\Facades\Cookie::class,
        'Crypt' => Illuminate\Support\Facades\Crypt::class,
        'DB' => Illuminate\Support\Facades\DB::class,
        'Eloquent' => Illuminate\Database\Eloquent\Model::class,
        'Event' => Illuminate\Support\Facades\Event::class,
        'File' => Illuminate\Support\Facades\File::class,
        'Gate' => Illuminate\Support\Facades\Gate::class,
        'Hash' => Illuminate\Support\Facades\Hash::class,
        'Lang' => Illuminate\Support\Facades\Lang::class,
        'Log' => Illuminate\Support\Facades\Log::class,
        'Mail' => Illuminate\Support\Facades\Mail::class,
        'Notification' => Illuminate\Support\Facades\Notification::class,
        'Password' => Illuminate\Support\Facades\Password::class,
        'Queue' => Illuminate\Support\Facades\Queue::class,
        'Redirect' => Illuminate\Support\Facades\Redirect::class,
        'Redis' => Illuminate\Support\Facades\Redis::class,
        'Request' => Illuminate\Support\Facades\Request::class,
        'Response' => Illuminate\Support\Facades\Response::class,
        'Route' => Illuminate\Support\Facades\Route::class,
        'Schema' => Illuminate\Support\Facades\Schema::class,
        'Session' => Illuminate\Support\Facades\Session::class,
        'Storage' => Illuminate\Support\Facades\Storage::class,
        'URL' => Illuminate\Support\Facades\URL::class,
        'Validator' => Illuminate\Support\Facades\Validator::class,
        'View' => Illuminate\Support\Facades\View::class,
        'Entrust' => Zizaco\Entrust\EntrustFacade::class,
        'Form' => Collective\Html\FormFacade::class,
        /* 'Input' => Illuminate\Support\Facades\Request::class, */
        'Excel' => Maatwebsite\Excel\Facades\Excel::class,
        'Image' => Intervention\Image\Facades\Image::class,
        'Zip' => ZanySoft\Zip\ZipFacade::class,
        'Debugbar' => Barryvdh\Debugbar\Facade::class,
        'AWS' => Aws\Laravel\AwsFacade::class,
        'Calendar' => MaddHatter\LaravelFullcalendar\Facades\Calendar::class,
        'Agent' => Jenssegers\Agent\Facades\Agent::class
    ],
];
