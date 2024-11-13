<?php
namespace routes;
use Domain\Contracts\Services\ChatServiceContracts;
use Domain\Contracts\Services\Directory\BankServiceContract;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Broadcast;
use Domain\Entities\Subscriber\Account;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Route;
use Core\Events\CoreEvent;
use Core\Events\ActiveAccountsEvent;

use Core\Exceptions\ApplicationException;
use Domain\Contracts\Services\AccountServiceContracts;
/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/



Route::post('broadcasting/auth', ['uses' => 'BroadcastController@authenticate']);
Route::get('broadcasting/auth', ['uses' => 'BroadcastController@authenticate']);


Route::group([
    'prefix' => 'broadcasting',
    'middleware' => ['jwt','cors','throttle:30,5'],
], function () {
    Route::post('/test', function () {
        Log::info("test-broadcasting");

        $company =auth()->user() ? auth()->user()->getCompany() : "";
        if (!$company) {
            throw new ApplicationException("Не найденеа компания");
        }
        $event = new CoreEvent(Request::get('channel_name'),Request::get('entity'),$company->getId());
        event($event);
        return $event->broadcastWith();
    });
});





Broadcast::channel('notifications.{idAccount}', function (Account $account,string $idAccount){
    Log::info('$broadcast->channel notifications.'.$idAccount);
    Log::info('Auth Account:'.$account->getId()->toString());
    if ($account->getId()->toString() === $idAccount) {
        return [
            "account"=>json_decode(help()->jms->toJson($account))
        ];
    } else {
        return false;
    }
    Log::info('==================$broadcast->channel notifications==============');
},['guard'=>["jwt"]]);

Broadcast::channel('newNotification',  function (Account $account) {
    if (auth()->user() === $account) {
        return true;
    }
    return false;
    Log::info("channel newNotification");
});

Broadcast::channel('chat.{idAccount}',  function (Account $account, $idAccount) {
    Log::info('==================$broadcast->channel chat==============');
    if ($account->getId()->serialize() === $idAccount) {
        return [
            "account"=>json_decode(help()->jms->toJson($account))
        ];
    } else {
        return false;
    }


},['guard'=>["jwt"]]);

/*
Broadcast::channel('chat.{idCompany}',  function (Account $account, $idCompany) {
    Log::info('chat.{'.$idCompany.'} account com'.$account->getCompany()->getId()->toString());
    if ($account->getCompany()->getId()->toString() == $idCompany) {
        return [
            "account"=>json_decode(help()->jms->toJson($account))
        ];

    } else {
        return false;
    }
},['guard'=>["jwt"]]);
*/

Broadcast::channel('online.{idCompany}',  function (Account $account, $idCompany) {
    Log::info('online.{idCompany}:'.$idCompany);
    if ($account->getCompany()->getId()->toString() == $idCompany) {
        Log::info('broadcast:ActiveAccountsEvent'.$account->getId());
        broadcast(new ActiveAccountsEvent($account))->toOthers();

        return [
            "data"=> json_decode(help()->jms->toJson($account)),
            "account"=>json_decode(help()->jms->toJson($account)),
            "company"=>$account->getCompany()->getId(),
            "username"=>$account->getUsername()
        ];
    } else {
        return  false;
    }

},['guard'=>["jwt"]]);

/*
Broadcast::channel('core.{channel}.{company}',  function ($account, $channel, $company) {
    Log::info("Broadcast::channel::core.{$channel} JOIN account id:".$account->getId()." authID:".auth()->user()->getId());
    Log::info("Company: ".$company);
    if ($channel == "user") {
        return [
            "username"=>auth()->user()->getUsername(),
            "id"=>auth()->user()->getId(),
            "company"=>auth()->user()->getCompany()->getId()
        ];
    }
    if ($channel == "test") {
        return [
            "channel"=>"test",
            "username"=>auth()->user()->getUsername(),
            "id"=>auth()->user()->getId(),
            "company"=>auth()->user()->getCompany()->getId()
        ];
    }

    return "false";
});
*/
