<?php
namespace Core\Http\Controllers;

use Auth;
use Core\Http\Controllers\Controller;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Business\BusinessServiceContracts;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Contracts\Broadcasting\Broadcaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Response;
use Core\Events\CoreEvent;
class BroadcastController extends Controller {
    private AccountServiceContracts $accountService;
    public function __construct(
        AccountServiceContracts $accountService
    ){
        $this->accountService = $accountService;
        $this->middleware('jwt');
    }
    public function authenticate(Request $request)
    {
        if ($request->hasSession()) {
            $request->session()->reflash();
        }
        Log::info(" ");
        Log::info("\e[33m=================\e[33mBroadcastController authenticate\e[33m==========================\e[39m");
        Log::info("\e[5mAccount\e[25m ID:".auth()->user()->getId()." Username:".auth()->user()->getUsername())." ";
        Log::info("=================\e[92mBroadcastController authenticate END\e[39m======================");
        Log::info(" ");

        return Broadcast::auth($request);
    }


    public function joinChat($room) {
        Log::info("BroadcastController joinChat:");
        return "joinChat";
    }
}
