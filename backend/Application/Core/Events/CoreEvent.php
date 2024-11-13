<?php

namespace Core\Events;

use Illuminate\Broadcasting\{
    Channel,
    InteractsWithSockets,
    PrivateChannel,
    PresenceChannel
};
use Core\Events\Event;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;


class CoreEvent extends Event implements ShouldBroadcast
{
    use  InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public  $event;
    public  $entity;
    private $company;

    /**
     * @param string $event
     * @param $entity
     */
    public function __construct(string $event, $entity,$company)
    {
        $this->event = $event;
        $this->entity = $entity;
        $this->company = $company;
        //$this->notificationService = new NotificationService();
    }
    public function broadcastAs()
    {
        return 'core.'.$this->event;
    }

    /**
     * Метод отвечает за возвращение каналов, на которые вещается событие
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel
    {

        return new PresenceChannel('core.'.$this->event.'.'.$this->company);
    }

    public function broadcastWith()
    {
        return [
            'event' => $this->event,
            'entity' => $this->entity,
            'compnay' => $this->company
        ];
    }

}
