<?php

namespace Core\Providers;

use Domain\Contracts\Repository\Crm\BrigadeRepositoryContracts;
use Domain\Contracts\Repository\Crm\ObjectsRepositoryContracts;
use Domain\Contracts\Repository\Crm\ProjectsRepositoryContracts;
use Domain\Contracts\Repository\Crm\SpecificationRequisitionRepositoryContracts;
use Domain\Contracts\Repository\Crm\TimesheetRepositoryContracts;
use Domain\Contracts\Repository\Crm\WorkpeopleRepositoryContract;
use Domain\Contracts\Repository\Directory\PartnersBankAccountRepositoryContract;
use Domain\Contracts\Repository\Directory\PartnersRepositoryContract;
use Domain\Contracts\Repository\Document\MaterialRequisitionRepositoryContracts;
use Domain\Contracts\Repository\Document\RequisitionMaterialRepositoryContract;
use Domain\Contracts\Services\Crm\BrigadeServiceContract;
use Domain\Contracts\Services\Crm\MasterServiceContract;
use Domain\Contracts\Services\Crm\ObjectsServiceContracts;
use Domain\Contracts\Services\Crm\ProjectsServiceContracts;
use Domain\Contracts\Services\Crm\WorkpeopleServiceContract;
use Domain\Contracts\Services\Directory\PartnersServiceContract;
use Domain\Services\Crm\BrigadeService;
use Domain\Services\Crm\MasterService;
use Domain\Services\Crm\ObjectsService;
use Domain\Services\Crm\ProjectsService;
use Domain\Services\Crm\WorkpeopleService;
use Domain\Services\Directory\PartnersService;
use Illuminate\Support\ServiceProvider;
use Infrastructure\Repositories\Crm\BrigadeRepository;
use Infrastructure\Repositories\Crm\ObjectsRepository;
use Infrastructure\Repositories\Crm\TimesheetRepository;
use Infrastructure\Repositories\Crm\WorkpeopleRepository;
use Infrastructure\Repositories\Directory\PartnersBankAccountRepository;
use Infrastructure\Repositories\Directory\PartnersRepository;
use Infrastructure\Repositories\Document\MaterialRequisitionMaterialRepository;
use Infrastructure\Repositories\Document\MaterialRequisitionRepository;


use Domain\Services\Crm\RequisitionService;
use Domain\Contracts\Services\Crm\RequisitionServiceContract;

class CrmServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(BrigadeRepositoryContracts::class, BrigadeRepository::class);
        $this->app->bind(TimesheetRepositoryContracts::class, TimesheetRepository::class);
        $this->app->bind(WorkpeopleRepositoryContract::class, WorkpeopleRepository::class);
        $this->app->bind(ObjectsRepositoryContracts::class, ObjectsRepository::class);
        $this->app->bind(PartnersRepositoryContract::class, PartnersRepository::class);
        $this->app->bind(PartnersBankAccountRepositoryContract::class, PartnersBankAccountRepository::class);

        $this->app->bind(BrigadeServiceContract::class, BrigadeService::class);
        $this->app->bind(WorkpeopleServiceContract::class, WorkpeopleService::class);
        $this->app->bind(ObjectsServiceContracts::class, ObjectsService::class);

        $this->app->bind(PartnersServiceContract::class, PartnersService::class);

        $this->app->bind(SpecificationRequisitionRepositoryContracts::class, SpecificationResourcesRepository::class);
        $this->app->bind(MaterialRequisitionRepositoryContracts::class, MaterialRequisitionRepository::class);

        $this->app->bind(RequisitionMaterialRepositoryContract::class, MaterialRequisitionMaterialRepository::class);

        $this->app->bind(MasterServiceContract::class, MasterService::class);

        $this->app->bind(RequisitionServiceContract::class, RequisitionService::class);


    }
}
