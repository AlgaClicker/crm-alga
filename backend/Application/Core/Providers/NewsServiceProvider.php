<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\Contracts\Services\NewsServiceContracts;
use Domain\Services\NewsService;

use Infrastructure\Repositories\News\NewsRepository;
use Domain\Contracts\Repository\NewsRepositoryContracts;

use Infrastructure\Repositories\News\CommentsRepository;
use Domain\Contracts\Repository\NewsCommentsRepositoryContracts;

class NewsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(NewsServiceContracts::class, NewsService::class);
        $this->app->bind(NewsRepositoryContracts::class, NewsRepository::class);
        $this->app->bind(NewsCommentsRepositoryContracts::class, CommentsRepository::class);
    }
}
