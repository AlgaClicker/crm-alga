<?php

namespace Core\Providers;

use Domain\Entities\Business\Directory\Bank;
use Illuminate\Support\ServiceProvider;

use Domain\Contracts\Repository\Directory\MaterialRepositoryContract;
use Domain\Contracts\Services\Directory\MaterialServiceContract;

use Infrastructure\Repositories\Directory\MaterialRepository;
use Domain\Services\Directory\MaterialService;

use Domain\Contracts\Repository\Utils\UnitsRepositoryContracts;
use Infrastructure\Repositories\Utils\UnitsRepository;

//=================================
use Domain\Contracts\Repository\Directory\BankRepositoryContract;
use Infrastructure\Repositories\Directory\BankRepository;
use Infrastructure\Repositories\Directory\BankRepositorySearch;
use Domain\Contracts\Repository\Directory\BankAccountRepositoryContract;
use Infrastructure\Repositories\Directory\BankAccountRepository;

use Domain\Contracts\Services\Directory\BankServiceContract;
use Domain\Services\Directory\BankService;

//================================
use Domain\Contracts\Repository\Directory\RecipientRepositoryContract;
use Infrastructure\Repositories\Directory\RecipientRepository;

use Domain\Contracts\Services\Directory\RecipientServiceContract;
use Domain\Services\Directory\RecipientService;

use Domain\Contracts\Repository\Directory\ProfessionRepositoryContract;
use Infrastructure\Repositories\Directory\ProfessionRepository;

use Domain\Contracts\Services\Directory\ProfessionServiceContract;
use Domain\Services\Directory\ProfessionService;



class DirectoryServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $this->app->bind(MaterialRepositoryContract::class, MaterialRepository::class);
        $this->app->bind(MaterialServiceContract::class, MaterialService::class);
        $this->app->bind(UnitsRepositoryContracts::class, UnitsRepository::class);

        //Справочник Должности
        $this->app->bind(ProfessionRepositoryContract::class, ProfessionRepository::class);
        $this->app->bind(ProfessionServiceContract::class, ProfessionService::class);

        //Справочник Банки
        $this->app->bind(BankRepositoryContract::class, BankRepository::class);
        $this->app->bind(BankAccountRepositoryContract::class, BankAccountRepository::class);
        $this->app->bind(BankServiceContract::class, BankService::class);

        //Справочник Контрагенты (партнеры)
        $this->app->bind(RecipientRepositoryContract::class, RecipientRepository::class);
        $this->app->bind(RecipientServiceContract::class, RecipientService::class);

        /*
        $this->app->bind(BankRepositoryContract::class, function($app) {
            return new BankRepositorySearch(
                $app['em'],
                $app['em']->getClassMetadata(Bank::class),
                $app->make(\Laravel\Scout\EngineManager::class)
            );
        });
        */

    }
}
