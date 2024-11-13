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

class ChatEventPrivate extends Event implements ShouldBroadcast
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
    }

    public function broadcastAs()
    {
        Log::info('ChatEvent::broadcastAs');
        return 'newMyMessage';
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
        return new PrivateChannel('chat.'.$this->chat->getRoom().'.my');
    }

    public function broadcastWith()
    {
        return [
            "room"=>$this->chat->getRoom(),
            "message"=>$this->chat->getMessage(),
            "account"=>$this->account->getId(),
            "messageBody"=>json_decode(help()->jms->toJson($this->chat)),
        ];
    }


}

