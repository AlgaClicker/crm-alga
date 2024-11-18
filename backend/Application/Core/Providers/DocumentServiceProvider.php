<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\Services\Document\WorkflowService;
use Domain\Contracts\Services\Document\WorkflowServiceContract;


use Infrastructure\Repositories\Document\WorkflowRepository;
use Domain\Contracts\Repository\Document\WorkflowRepositoryContract;

use Infrastructure\Repositories\Document\TasksRepository;
use Domain\Contracts\Repository\Document\TasksRepositoryContract;

use Infrastructure\Repositories\Document\ContractsRepository;
use Domain\Contracts\Repository\Document\ContractsRepositoryContract;

use Domain\Contracts\Services\Document\ContractsServiceContract;
use Domain\Services\Document\ContractsService;



use Domain\Contracts\Repository\Document\InvoiceMaterialsConfirmedRequisitionRepositoryContract;
use Infrastructure\Repositories\Document\InvoiceMaterialsConfirmedRequisitionRepository;

class DocumentServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(WorkflowRepositoryContract::class, WorkflowRepository::class);
        $this->app->bind(TasksRepositoryContract::class, TasksRepository::class);
        $this->app->bind(ContractsRepositoryContract::class, ContractsRepository::class);
       // $this->app->bind(InvoiceMaterialsConfirmedRequisitionRepositoryContract::class, InvoiceMaterialsConfirmedRequisitionRepository::class);

        $this->app->bind(WorkflowServiceContract::class, WorkflowService::class);
        $this->app->bind(ContractsServiceContract::class, ContractsService::class);
    }
}
