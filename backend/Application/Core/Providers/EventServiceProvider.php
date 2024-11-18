<?php

namespace Core\Providers;

use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Core\Events\NotificationEvent::class => [
            Core\Listeners\NotificationListener::class,
        ],
        Core\Events\NewsEvent::class => [
            Core\Listeners\NewsListener::class,
        ],

        Core\Events\ChatEvent::class => [
            Core\Listeners\ChatListener::class,
        ],
        Core\Events\ChatEventPrivate::class => [
            Core\Listeners\ChatListener::class,
        ],

        Core\Events\CoreEvent::class => [
            Core\Listeners\CoreListener::class,
        ],
        Core\Events\ActiveAccountsEvent::class => [
            Core\Listeners\ActiveAccountsListener::class,
        ]



    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents()
    {

        return false;
    }
}
