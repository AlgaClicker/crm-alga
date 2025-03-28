<?php

namespace Core\Listeners;

use Core\Events\ChatEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
class ChatListener
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
    public function handle(ChatEvent $event)
    {
        //
        Log::info("ChatEvent::handle");
        return $event;
    }
}
