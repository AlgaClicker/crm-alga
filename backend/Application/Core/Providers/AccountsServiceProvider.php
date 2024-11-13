<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Repository\AccountsRepositoryContracts;
use Domain\Services\AccountService;
use Infrastructure\Repositories\Account\AccountsRepository;

use Infrastructure\Repositories\Account\RolesRepository;
use Domain\Contracts\Repository\RolesRepositoryContracts;

use Domain\Contracts\Repository\AccountTokensRepositoryContract;
use Infrastructure\Repositories\Account\AccountTokensRepository;

use Infrastructure\Repositories\Account\OptionsRepository;
use Domain\Contracts\Repository\AccountOptionsRepositoryContract;

class AccountsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(AccountServiceContracts::class, AccountService::class);
        $this->app->bind(AccountsRepositoryContracts::class, AccountsRepository::class);
        $this->app->bind(AccountTokensRepositoryContract::class, AccountTokensRepository::class);
        $this->app->bind(RolesRepositoryContracts::class, RolesRepository::class);
        $this->app->bind(AccountOptionsRepositoryContract::class, OptionsRepository::class);
    }
}
