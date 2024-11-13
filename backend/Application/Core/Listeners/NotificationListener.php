<?php

namespace Core\Listeners;

use Core\Events\NotificationEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
class NotificationListener
{
    public function handle(NotificationEvent $event)
    {
        Log::info("NotificationListener::handle");
        return $event;
    }
}

