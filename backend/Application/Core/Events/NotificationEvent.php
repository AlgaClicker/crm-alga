<?php

namespace Core\Events;

use Illuminate\Broadcasting\{
    Channel,
    InteractsWithSockets,
    PrivateChannel,
    PresenceChannel
};
use Core\Events\Event;
use Domain\Entities\Subscriber\Account;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Domain\Contracts\Services\NotificationServiceContracts;
use Domain\Entities\Services\Notification;
use Illuminate\Support\Facades\Log;
class NotificationEvent extends Event implements ShouldBroadcast
{
    use  InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public $notification;
    private Account $account;

    private NotificationServiceContracts $notificationService;

    /**
     * @param array $notification
     */
    public function __construct(Notification $notification)
    {
        Log::info("NotificationEvent:__construct()");
        if (auth()->user()) {
            $this->account = auth()->user();
        }

        $this->notification = $notification;
    }

    /**
     * Метод отвечает за возвращение каналов, на которые вещается событие
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        Log::info("NotificationEvent:broadcastOn()");
        //Log::info(auth()->user()->getId()->serilize());
        //return new Channel('notifications');
        Log::info($this->notification->getToAccount()->getId()->serialize());
        return [
            new PresenceChannel('notifications.'.$this->notification->getToAccount()->getId()->serialize())
        ];

    }

    public function broadcastAs()
    {
        //return microtime(true);
        //return $this->notification;
        return 'newNotification';
    }

    public function broadcastWith() {
        return [
            "notification"=>json_decode(help()->jms->toJson($this->notification)),
        ];
        //Log::info("Account ID:");
        //Log::info("NotificationEvent:broadcastWith()");

    }


}
