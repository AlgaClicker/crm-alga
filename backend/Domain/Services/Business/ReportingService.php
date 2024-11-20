<?php

namespace Domain\Services\Business;
use Domain\Contracts\Repository\Crm\WorkpeopleRepositoryContract;
use Domain\Contracts\Repository\Payments\InvoicesRepositoryContract;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Business\ReportingServiceContracts;
use Domain\Contracts\Repository\Crm\BrigadeRepositoryContracts;
use Domain\Contracts\Repository\Reporting\BrigadesReportingRepositoryContract;
use Domain\Contracts\Repository\Reporting\ReportingRepositoryContract;
use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Ramsey\Uuid\Uuid;

class ReportingService implements ReportingServiceContracts
{
    private WorkpeopleRepositoryContract $workpeopleRepository;
    private BrigadeRepositoryContracts $brigadesRepository;
    private InvoicesRepositoryContract $invoicesRepository;
    private BrigadesReportingRepositoryContract $brigadesReportingRepository;
    private ReportingRepositoryContract $reportingRepository;
    private AccountServiceContracts $accountService;
    private SpecificationsServiceContracts $specificationsService;

    public function __construct(
        BrigadeRepositoryContracts $brigadesRepository,
        WorkpeopleRepositoryContract $workpeopleRepository,
        InvoicesRepositoryContract $invoicesRepository,
        BrigadesReportingRepositoryContract $brigadesReportingRepository,
        ReportingRepositoryContract $reportingRepository,
        AccountServiceContracts $accountService,
        SpecificationsServiceContracts $specificationsService

    ) {
        $this->workpeopleRepository = $workpeopleRepository;
        $this->invoicesRepository = $invoicesRepository;
        $this->brigadesReportingRepository = $brigadesReportingRepository;
        $this->brigadesRepository = $brigadesRepository;
        $this->reportingRepository = $reportingRepository;
        $this->accountService = $accountService;
        $this->specificationsService = $specificationsService;
    }

    public function income($options) {

        return ['data'=>"tets",'options'=>"dsf"];
    }

    public function repostBrigades($arrKeyValue) {
        $data = [];
        $options = [];
        $brigade = "";
        if (array_key_exists("idBrigade",$arrKeyValue)) {
            $brigade = $this->brigadesRepository->findByUuid($arrKeyValue['idBrigade']);

        }
        $stDate = new \DateTimeImmutable($arrKeyValue['startDate']);
        $endDate = new \DateTimeImmutable($arrKeyValue['endDate']);
        $this->brigadesReportingRepository->index($stDate,$endDate);
        return [
            "data" => $brigade,
            "options" => $options
        ];
    }

    public function reportSpecification($arrKeyValue) {
        # Список специфкаций в работе, поступления и расходы
        # Если указан период startDate-endDate вывод активных (по которым проходили финансовые операции) за этот период
        # Если указана спецификация specificationId выводить список инвойсов
        $specificationId = null;

        $startDate = (new \DateTimeImmutable())->modify("first day of");
        $endDate = (new \DateTimeImmutable())->modify("last day of");

        $startDate = $startDate->setDate($startDate->format('Y'),1,1);

        if (array_key_exists("startDate",$arrKeyValue)) {

            $startDate = new \DateTimeImmutable($arrKeyValue['startDate']);
        }
        if (array_key_exists("endDate",$arrKeyValue)) {
            $endDate = (new \DateTimeImmutable($arrKeyValue['endDate']))->modify("tomorrow");
        }

        if (array_key_exists("specificationId",$arrKeyValue)) {
            $specificationId = $this->specificationsService->getSpecificationOnly($arrKeyValue['specificationId']);
        }



        if (array_key_exists("specifications",$arrKeyValue)) {
            if (is_array($arrKeyValue['specifications'])) {
                $listSpec = new Collection();


                foreach ($arrKeyValue['specifications'] as $spec) {
                    if (is_array($spec) && array_key_exists('id',$spec) && Uuid::isValid($spec['id'])) {
                        $specificationId = $this->specificationsService->getSpecificationOnly($spec['id']);
                        $specCount = $this->reportingRepository->reportSpecification($startDate,$endDate,$specificationId);
                        if ($specCount) {
                            $listSpec->add($specCount);
                        }
                    }
                    if (is_string($spec) && Uuid::isValid($spec)) {
                        $specificationId = $this->specificationsService->getSpecificationOnly($spec);
                        $specCount = $this->reportingRepository->reportSpecification($startDate,$endDate,$specificationId);
                        if ($specCount) {
                            $listSpec->add($specCount);
                        }

                    }
                }

                return $listSpec->all();
            }

        }

        return $this->reportingRepository->reportSpecification($startDate,$endDate,$specificationId);
    }


    public function accountsActive($arrKeyValue) {
        $accounts = $this->accountService->getAll();
        $options = [];

        if (array_key_exists('options',$arrKeyValue)) {
            $options = $arrKeyValue['options'];
        }
        if (array_key_exists('accounts',$arrKeyValue)) {
            $listAccounts = [];

            foreach ( $arrKeyValue['accounts']as $item) {
                if (is_array($item) && array_key_exists("id",$item)) {
                    $listAccounts[] = $item['id'];
                }
            }
            return $this->reportingRepository->activeAccounts($listAccounts,$options);
        }
        if (array_key_exists('id',$arrKeyValue)) {
            if (is_array($arrKeyValue['id'])) {

            } else {
                $accounts = [$this->accountService->getAccountMyCompnay($arrKeyValue['id'])];
            }

        }
        return $this->reportingRepository->activeAccounts($accounts,$options);
    }
}
