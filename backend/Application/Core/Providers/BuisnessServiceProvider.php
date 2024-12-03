<?php

namespace Core\Providers;

use Illuminate\Support\ServiceProvider;
use Domain\Services\Business\BusinessService;
use Domain\Contracts\Repository\Business\CompanyRepositoryContracts;
use Domain\Contracts\Repository\Business\CompanyBankAccountsRepositoryContracts;

use Domain\Contracts\Services\Business\CompanyServiceContract;
use Domain\Services\Business\CompanyService;


use Domain\Contracts\Repository\Business\StockRepositoryContracts;
use Domain\Contracts\Services\Business\BusinessServiceContracts;
use Infrastructure\Repositories\Business\CompanyRepository;
use Infrastructure\Repositories\Business\CompanyBankAccountsRepository;
use Infrastructure\Repositories\Business\StockRepository;

use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Domain\Services\Business\SpecificationService;
use Domain\Contracts\Repository\Crm\SpecificationRepositoryContracts;
use Infrastructure\Repositories\Crm\SpecificationRepository;
use Infrastructure\Repositories\Crm\SpecificationMaterialRepository;
use Domain\Contracts\Repository\Crm\SpecificationMaterialRepositoryContracts;

use Infrastructure\Repositories\Crm\SpecificationResourcesRepository;
use Domain\Contracts\Repository\Crm\SpecificationResourcesRepositoryContracts;

use Infrastructure\Repositories\Crm\SpecificationTypeWorksRepository;
use Domain\Contracts\Repository\Crm\SpecificationTypeWorksRepositoryContracts;

use Domain\Contracts\Services\Business\ReportingServiceContracts;
use Domain\Services\Business\ReportingService;

use Domain\Contracts\Repository\Reporting\BrigadesReportingRepositoryContract;
use Infrastructure\Repositories\Reporting\BrigadesReportingRepository;

use Domain\Contracts\Repository\Reporting\ReportingRepositoryContract;
use Infrastructure\Repositories\Reporting\ReportingRepository;

use Domain\Contracts\Repository\Crm\Specification\MaterialCalculationRepositoryContracts;
use Infrastructure\Repositories\Crm\Specification\MaterialCalculationRepository;



use Domain\Contracts\Repository\Document\InvoicesRequisitionRepositoryContract;
use Infrastructure\Repositories\Document\InvoicesRequisitionRepository;


class BuisnessServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {


        $this->app->bind(CompanyRepositoryContracts::class, CompanyRepository::class);
        $this->app->bind(CompanyBankAccountsRepositoryContracts::class, CompanyBankAccountsRepository::class);

        $this->app->bind(CompanyServiceContract::class, CompanyService::class);

        $this->app->bind(StockRepositoryContracts::class, StockRepository::class);

        $this->app->bind(BusinessServiceContracts::class, BusinessService::class);

        $this->app->bind(SpecificationRepositoryContracts::class, SpecificationRepository::class);
        $this->app->bind(SpecificationMaterialRepositoryContracts::class, SpecificationMaterialRepository::class);
        $this->app->bind(SpecificationResourcesRepositoryContracts::class, SpecificationResourcesRepository::class);
        $this->app->bind(SpecificationTypeWorksRepositoryContracts::class, SpecificationTypeWorksRepository::class);

        $this->app->bind(MaterialCalculationRepositoryContracts::class, MaterialCalculationRepository::class);

        $this->app->bind(SpecificationsServiceContracts::class, SpecificationService::class);

        $this->app->bind(ReportingRepositoryContract::class, ReportingRepository::class);
        $this->app->bind(ReportingServiceContracts::class, ReportingService::class);
        $this->app->bind(BrigadesReportingRepositoryContract::class, BrigadesReportingRepository::class);

        $this->app->bind(InvoicesRequisitionRepositoryContract::class, InvoicesRequisitionRepository::class);


    }
}
