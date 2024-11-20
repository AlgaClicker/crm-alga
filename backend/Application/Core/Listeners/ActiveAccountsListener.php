<?php

namespace Core\Listeners;

use Core\Events\ActiveAccountsEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
class ActiveAccountsListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        Log::info("ActiveAccountsListener::__construct");
        //
    }

    /**
     * Handle the event.
     *
     * @param  Core\Events\ActiveAccountsEvent  $account
     * @return void
     */
    public function handle(ActiveAccountsEvent $account)
    {
        //
        Log::info("ActiveAccountsListener::handle");
        return $account;
    }
}
