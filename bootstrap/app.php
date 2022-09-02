<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    realpath(__DIR__.'/../')
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

$app->bind('path.public', function() {
    return base_path().'/public_html';
});

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'dev.ofreg.ky' || $_SERVER['HTTP_HOST'] == 'www.dev.ofreg.ky')) {
    $envFile = '.env.dev';
} elseif (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'stage.ofreg.ky' || $_SERVER['HTTP_HOST'] == 'www.stage.ofreg.ky')) {
    $envFile = '.env.stage';
} elseif (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'ofreg.ky' || $_SERVER['HTTP_HOST'] == 'www.ofreg.ky')) {
    $envFile = '.env';
} elseif (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] == 'ofreg.netcluesdemo.com' || $_SERVER['HTTP_HOST'] == 'www.ofreg.netcluesdemo.com')) {
    $envFile = '.env.demo';
} else {
    $envFile = '.env.local';
}
$app->loadEnvironmentFrom($envFile);



return $app;
