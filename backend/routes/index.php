<?php
namespace routes;

/** @var \Laravel\Lumen\Routing\Router $router */

use Domain\Entities\Subscriber\Roles;
use Illuminate\Support\Facades\Route;
use Core\Events\NotificationEvent;
use Domain\Entities\Services\Notification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Collection;
/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/


function _getInfoRoute()
{


    return [
        "name"=>env('APP_NAME','Alga-CRM'),
        "version"=>env('VERSION','no ver ENV'),
        "auth"=>"/auth/login",
        "jwt"=>"/api/v1/",
        "microtime"=>str(round(microtime(true) - app()->run_app,4))
    ];
}

$router->get('/', function () use ($router) {
    return _getInfoRoute();
});
$router->post('/', function () use ($router) {
    return _getInfoRoute();
});


$router->group(['middleware' => ['api','throttle:30,1'],'prefix' => 'api'], function () use ($router) {

    $router->post('/', function () {
        return _getInfoRoute();
    });

    $router->get('/', function () {

        return _getInfoRoute();
    });


});

require 'channels.php';

Route::group([
    'middleware' => ['cors','throttle:60,1'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', ['as' => 'authLogin', 'uses' =>'AuthController@login']);

    //signInWithRefreshToken
    Route::post('/refresh-token', ['as' => 'signInWithRefreshToken', 'uses' =>'AuthController@signInWithRefreshToken']);
    Route::post('registration', ['as' => 'registration', 'uses' =>'AuthController@registration']);
    Route::get('registration/confirm', ['as' => 'registrationConfirm', 'uses' =>'AuthController@registrationConfirm']);
    Route::post('broadcasting', ['as' => 'broadcastingAuth', 'uses' =>'BroadcastController@authenticate']);
});


Route::group([
    'middleware' => ['jwt','cors','throttle:800,1'],
    'prefix' => '/auth'
], function ($router) {
    Route::post('/logout', ['as' => 'logout', 'uses' =>'AuthController@logout']);
    Route::post('/refresh', ['as' => 'authRefreshToken', 'uses' =>'AuthController@refresh']);

    Route::post('/me', ['as' => 'profile', 'uses' =>'AuthController@me']);
    Route::post('/me/firebase', ['as' => 'profile', 'uses' =>'AuthController@meFirebase']);
    Route::post('/account', ['as' => 'profileFirebase', 'uses' =>'AuthController@me']);

});

Route::get('/download/view/{hash}', ['as' => 'viewInfoFilePagePublic', 'uses' =>'ServicesController@viewInfoFilePage']);
Route::get('/download/{hash}', ['as' => 'loadFilePublic', 'uses' =>'ServicesController@loadFilePublic']);

# throttle 1200 запросов на минуту
Route::group([
    'prefix' => 'api/v1/',
    'middleware' => ['jwt','cors','throttle:1200,1'],
], function ($router) {
    Route::post('routes', function (){
        $routes = Route::getRoutes();
        $listRoutes = array();
        $num = 0;
        $html = "List Routes: <br>\r\n ";
        $collect = new Collection();
        foreach ($routes as $key=>$val){
            $num++;
            $endpoint = [
                "idx" => $num,
                "method"=> $val['method'],
                "uri"=>$val['uri'],
                "as"=> array_key_exists('as',$val['action']) ? $val['action']['as'] : '',
            ];
            if ($val) {
                $collect->add($endpoint);
            }

        }
        return $collect->all();
    });

    Route::post('/', function (){
        return "Api v1 ";
    });

    Route::group(['prefix' => '/document',],function () {
        require(__DIR__.'/document.php');
    });

    Route::group([
        'prefix' => '/notification',
    ], function ($router) {
        Route::get('/', function (){
            return "Api v1 notification";
        });
        require(__DIR__.'/notification.php');
    });

    Route::group([
        'prefix' => '/buisness',
    ], function ($router) {
        Route::post('/', function (){
            return "Api v1 buisness";
        });
        require(__DIR__.'/accounts.php');
        require(__DIR__.'/company.php');
    });


    Route::group([
        'prefix' => '/chat',
    ], function ($router) {
        Route::post('/', function (){
            return "Api v1 chat service";
        });
        require(__DIR__.'/chat.php');
    });

    Route::group([
        'prefix' => '/directory',
    ], function ($router) {
        Route::post('/', function (){
            return "Api v1 buisness directory";
        });
        require(__DIR__.'/directory.php');
    });

    Route::group([
        'prefix' => '/crm',
    ], function ($router) {
        Route::post('/', function (){
            return "Api v1 buisness crm";
        });

        Route::group(['prefix' => '/master',],function () {
            require(__DIR__.'/master.php');
        });

        Route::group(['prefix' => '/snabzheniye'],function () {
            require(__DIR__.'/snabzheniye.php');
        });





        require(__DIR__.'/crm.php');
        require(__DIR__.'/specification.php');

        Route::group([
            'prefix' => '/reporting',
        ], function ($router) {
            require(__DIR__.'/reporting.php');
        });
    });

    Route::group([
        'prefix' => '/services',
    ], function ($router) {
        Route::post('/', function (){
            return "Api v1 buisness crm";
        });
        require(__DIR__.'/services.php');
    });

});

/*
Route::group([
    'middleware' => ['jwt','throttle:30,5'],
    'prefix' => 'news',
], function ($router) {
    Route::get('/', 'NewsController@actionIndex');
    Route::post('/new', 'NewsController@actionCreateNews');
    Route::post('/add', 'NewsController@actionCreateNews');

    Route::get('/{idNews:[0-9]+}', 'NewsController@actionShowNews');
    Route::get('/{idNews:[0-9]+}/comments', 'NewsController@actionShowNewsComments');
    Route::post('/{idNews:[0-9]+}', 'NewsController@actionShowNews');
    Route::post('/', 'NewsController@actionIndex');

    Route::put('/{idNews}', 'NewsController@actionUpdateNews');
    Route::delete('/{idNews}', 'NewsController@actionDeleteNews');
    Route::post('/{idNews:[0-9]+}/add', 'NewsController@actionCreateComments');
});
*/


