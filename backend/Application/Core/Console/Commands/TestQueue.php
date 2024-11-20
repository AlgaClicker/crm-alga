<?php
namespace Core\Console\Commands;
use Exception;
use Event;

use Illuminate\Console\Command;
use Core\Events\NotificationEvent;
use Core\Events\ActiveAccountsEvent;
use Domain\Entities\Services\Notification;
use Illuminate\Support\Facades\Log;


class TestQueue extends Command
{
    protected $signature = "test:queue";
    protected $description = "Test queue";
    public function handle()
    {
        $notification = new Notification();

        $notification->setTitle('set Title broadcast');
        $notification->setMessage("set  message broadcast");
        $notification->setMethods("set  Method is notification broadcast");
        Log::info("TestQueue send BROADCAST");
        broadcast(new NotificationEvent($notification));

        $notification = new Notification();

        $notification->setTitle('set Title event');
        $notification->setMessage("set  message event");
        $notification->setMethods("set  Method is notification event");

        Log::info("TestQueue send EVENT");
        event(new NotificationEvent($notification));

        $this->info("notification");
    }
}
