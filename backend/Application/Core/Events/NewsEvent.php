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
use Domain\Entities\News\News;

class NewsEvent extends Event implements ShouldBroadcast
{
    use  InteractsWithSockets, SerializesModels;

    /**
     * @var array
     */
    public News $news;

    /**
     * @param array $notification
     */
    public function __construct(News $news)
    {
        $this->news = $news;
        //$this->notificationService = new NotificationService();
    }
    public function broadcastAs()
    {
        return 'addNews';
    }

    /**
     * Метод отвечает за возвращение каналов, на которые вещается событие
     *
     * @return Channel|array
     */
    public function broadcastOn(): Channel
    {

        return new Channel('news');
    }

    public function broadcastWith(): array
    {
        return [
            'id' => $this->news->getId(),
            'title' => $this->news->getTitle(),
            'createAt' => $this->news->getCreatedAt()
        ];
    }

}
