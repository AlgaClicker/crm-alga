<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\Contracts\Services\FileServiceContracts;
use Domain\Services\FileService;

use Infrastructure\Repositories\Services\FileRepository;
use Domain\Contracts\Repository\Services\FileRepositoryContracts;



class FilesServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(FileRepositoryContracts::class, FileRepository::class);
        $this->app->bind(FileServiceContracts::class, FileService::class);
        
    }
}
