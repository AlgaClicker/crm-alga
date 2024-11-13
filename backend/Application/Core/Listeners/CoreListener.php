<?php

namespace Core\Listeners;

use Core\Events\CoreEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
class CoreListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Core\Events\CoreEvent  $event
     * @return void
     */
    public function handle(CoreEvent $event)
    {
        //
        Log::info("CoreListener::handle");
        return $event;
    }
}
