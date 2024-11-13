<?php

namespace Core\Providers;

use Closure;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Domain\Services\Directory\BankService;
use Core\Jobs\LoadBankCbrJob;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton(\Illuminate\Contracts\Routing\ResponseFactory::class, function() {
            return new \Laravel\Lumen\Http\ResponseFactory();
        });

    }


    public function boot(): void
    {
    }


    public function loadRoutesFrom($location){
        return $location;
    }

}
