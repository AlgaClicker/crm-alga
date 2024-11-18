<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\Contracts\Services\NotificationServiceContracts;
use Domain\Services\NotificationService;

use Infrastructure\Repositories\Services\NotificationRepository;
use Domain\Contracts\Repository\Services\NotificationRepositoryContracts;



class NotificationServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(NotificationRepositoryContracts::class, NotificationRepository::class);
        $this->app->bind(NotificationServiceContracts::class, NotificationService::class);
        
    }
}
