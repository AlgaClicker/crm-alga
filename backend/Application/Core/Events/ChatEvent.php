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
use Domain\Entities\Services\Chat;
use Illuminate\Support\Facades\Log;

class ChatEvent extends Event implements ShouldBroadcast
{
    use  InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public  Chat $chat;
    private Account $account;

    /**
     * @param string $event
     * @param $entity
     */
    public function __construct(Chat $chat)
    {

        Log::info("ChatEvent::__construct");
        Log::info($chat->getMessage());
        Log::info($chat->getRoom());
        $this->chat = $chat;
        $this->account = auth()->user();
        //$this->notificationService = new NotificationService();
    }

    public function broadcastAs()
    {
        Log::info('ChatEvent::broadcastAs');
        //Log::info($this->chat->getId());
        if ($this->chat->getRequisition()) {
            return 'newMessageReq';
        } else {
            return 'newMessage';
        }

    }

    /**
     * Метод отвечает за возвращение каналов, на которые вещается событие
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel
    {
        Log::info('ChatEvent::broadcastOn');
        Log::info('chat.'.$this->chat->getRoom());
        return new PrivateChannel('chat.'.$this->chat->getAccountTo()->getId()->serialize());
    }

    public function broadcastWith()
    {
        if ($this->chat->getRequisition()) {
            return [
                "autor"=>json_decode(help()->jms->toJson($this->chat->getAutor())),
                "account_to"=>json_decode(help()->jms->toJson($this->chat->getAccountTo())),
                "messageBody"=>json_decode(help()->jms->toJson($this->chat)),
                "requisition"=>json_decode(help()->jms->toJson($this->chat->getRequisition())),
            ];
        }

        return [
            "autor"=>json_decode(help()->jms->toJson($this->chat->getAutor())),
            "account_to"=>json_decode(help()->jms->toJson($this->chat->getAccountTo())),
            "messageBody"=>json_decode(help()->jms->toJson($this->chat)),
        ];
    }


}

