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
use Domain\Entities\Business\Company\Company;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Scheduling\Schedule;


class ActiveAccountsEvent extends Event implements ShouldBroadcast
{
    use  InteractsWithSockets, SerializesModels;

    /**
     * @var string $room
     */
    private string $room;
    /**
     * @var Account $account
     */
    private Account $account;
    /**
     * @var Company $company
     */
    private Company $company;

    /**
     * @param Company $company
     */
    public function __construct(Account $account)
    {

        $this->company = $account->getCompany();
        $this->account = $account;
        $this->room =  $this->company->getId()->toString();
        Log::info('ActiveAccountsEvent::__construct Account:'.$this->company->getName());
    }

    public function broadcastAs()
    {
        Log::info('ActiveAccountsEvent::broadcastAs');
        return 'user.online';
    }

    /**
     * Метод отвечает за возвращение каналов, на которые вещается событие
     *
     * @return Channel|array
     */
    public function broadcastOn()
    {
        Log::info('ActiveAccountsEvent::broadcastOn'.': chat.'.$this->account->getId());
         return [new PresenceChannel('online.'.$this->company->getId())];

    }

    public function broadcastWhen() {
        return [
            "account"=>$this->account->getId(),
            "username"=>$this->account->getUsername(),
            "object"=>"ActiveAccountsEvent"
        ];
    }


/*
    public function broadcastWhen() {
        Log::info('ActiveAccountsEvent::broadcastWhen chat.'.$this->room);

        return [
            "room"=>$this->room,
            "account"=>$this->company->getId()
        ];
    }
*/
}
