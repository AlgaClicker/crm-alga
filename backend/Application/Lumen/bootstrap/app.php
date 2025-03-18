<?php

require_once __DIR__.'/../../../vendor/autoload.php';

(new Laravel\Lumen\Bootstrap\LoadEnvironmentVariables(
    dirname(__DIR__)
))->bootstrap();

date_default_timezone_set(env('APP_TIMEZONE', 'UTC'));

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| Here we will load the environment and create the application instance
| that serves as the central piece of this framework. We'll use this
| application as an "IoC" container and router for this framework.
|
*/

$app = new Laravel\Lumen\Application(
    dirname(__DIR__)
);
$app->run_app = microtime(true);
$app->withFacades();


$app->instance('./../config', app()->basePath() . DIRECTORY_SEPARATOR . 'config');
if ( ! function_exists('config_path')) {
    function config_path($path = '')
    {
        return app()->basePath() . './../config' . ($path ? '/' . $path : $path);
    }
}
$app->withEloquent();

/*
|--------------------------------------------------------------------------
| Register Container Bindings
|--------------------------------------------------------------------------
|
| Now we will register a few bindings in the service container. We will
| register the exception handler and the console kernel. You may add
| your own bindings here if you like or you can make another file.
|
*/

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    Core\Exceptions\Handler::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    Core\Console\Kernel::class
);

/*
|--------------------------------------------------------------------------
| Register Config Files
|--------------------------------------------------------------------------
|
| Now we will register the "app" configuration file. If the file exists in
| your configuration directory it will be loaded; otherwise, we'll load
| the default version. You may register other files below as needed.
|
*/


$app->configure('app');
$app->configure('database');
$app->configure('queue');
$app->configure('mail');
$app->configure('jwt');
$app->configure('firebase');

/*

$app->configure('auth');
$app->configure('doctrine');
*/

/*
|--------------------------------------------------------------------------
| Register Middleware
|--------------------------------------------------------------------------
|
| Next, we will register the middleware with the application. These can
| be global middleware that run before and after each request into a
| route or middleware that'll be assigned to some specific routes.
|
*/

$app->middleware([
//     App\Http\Middleware\ExampleMiddleware::class
    Core\Http\Middleware\CORSMiddleware::class
]);



$app->routeMiddleware([
     'web' => Core\Http\Middleware\Authenticate::class,
     'api' => Core\Http\Middleware\Authenticate::class,
     'jwt' => Core\Http\Middleware\JWTMiddleware::class,
     'cors' => Core\Http\Middleware\CORSMiddleware::class,
     'csrf' => Laravel\Lumen\Http\Middleware\VerifyCsrfToken::class,
     'throttle' => GrahamCampbell\Throttle\Http\Middleware\ThrottleMiddleware::class,
     'auth' => App\Http\Middleware\Authenticate::class,
]);

/*
|--------------------------------------------------------------------------
| Register Service Providers
|--------------------------------------------------------------------------
|
| Here we will register all of the application's service providers which
| are used to bind services into the container. Service providers are
| totally optional, so you are not required to uncomment this line.
|
*/

$app->register(Core\Providers\AppServiceProvider::class);
$app->register(\Illuminate\Redis\RedisServiceProvider::class);



class_alias('LaravelDoctrine\ORM\Facades\EntityManager', 'EntityManager');
class_alias('LaravelDoctrine\ORM\Facades\Registry', 'Registry');
class_alias('LaravelDoctrine\ORM\Facades\Doctrine', 'Doctrine');




$app->register(GrahamCampbell\Throttle\ThrottleServiceProvider::class);
$app->withFacades(true, [
    Tymon\JWTAuth\Facades\JWTAuth::class => 'JWTAuth',
    Tymon\JWTAuth\Facades\JWTFactory::class => 'JWTFactory',
]);
$app->register(Core\Providers\AuthServiceProvider::class);
$app->register(Tymon\JWTAuth\Providers\LumenServiceProvider::class);



$app->register(LaravelDoctrine\ORM\DoctrineServiceProvider::class);


$app->register(LaravelDoctrine\Migrations\MigrationsServiceProvider::class);
$app->register(LaravelDoctrine\Extensions\GedmoExtensionsServiceProvider::class);
$app->register(LaravelDoctrine\ORM\Auth\Passwords\PasswordResetServiceProvider::class);
$app->register(LaravelDoctrine\ACL\AclServiceProvider::class);

#TODO
//$app->register(Laravel\Scout\ScoutServiceProvider::class);
//$app->register(LaravelDoctrine\Scout\ScoutServiceProvider::class);




## CrmServiceProvider
$app->register(Core\Providers\NewsServiceProvider::class);
$app->register(Core\Providers\AccountsServiceProvider::class);
$app->register(Core\Providers\BuisnessServiceProvider::class);
$app->register(Core\Providers\FilesServiceProvider::class);
$app->register(Core\Providers\CrmServiceProvider::class);
$app->register(Core\Providers\NotificationServiceProvider::class);
$app->register(Core\Providers\DirectoryServiceProvider::class);
$app->register(Core\Providers\DocumentServiceProvider::class);
$app->register(Core\Providers\PaymentServiceProvider::class);

$app->register(Illuminate\Mail\MailServiceProvider::class);


$app->register(Core\Providers\BroadcastServiceProvider::class);
$app->register(Core\Providers\EventServiceProvider::class);

$app->register(Core\Providers\ChatServiceProvider::class);

$app->register(MoveMoveIo\DaData\DaDataServiceProvider::class);

try {
    $app->register(Kreait\Laravel\Firebase\ServiceProvider::class);
} catch (Exception $e) {

}

$app->register(Illuminate\Queue\QueueServiceProvider::class);
$app->alias('mail.manager', Illuminate\Mail\MailManager::class);
$app->alias('mail.manager', Illuminate\Contracts\Mail\Factory::class);
$app->alias('mailer', Illuminate\Mail\Mailer::class);
$app->alias('mailer', Illuminate\Contracts\Mail\Mailer::class);
$app->alias('mailer', Illuminate\Contracts\Mail\MailQueue::class);

/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes file so that they can all be added to
| the application. This will provide all of the URLs the application
| can respond to, as well as the controllers that may handle them.
|
*/

$app->router->group([
    'namespace' => 'Core\Http\Controllers',
], function ($router) {
    require __DIR__.'/../../../routes/index.php';
});




return $app;
