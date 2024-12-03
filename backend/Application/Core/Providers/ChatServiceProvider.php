<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\Contracts\Services\ChatServiceContracts;
use Domain\Services\ChatService;

use Infrastructure\Repositories\Services\ChatRepository;
use Domain\Contracts\Repository\Services\ChatRepositoryContracts;



class ChatServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(ChatRepositoryContracts::class, ChatRepository::class);
        $this->app->bind(ChatServiceContracts::class, ChatService::class);
    }
}
