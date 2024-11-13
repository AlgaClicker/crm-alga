<?php
namespace Domain\Services\Directory;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Directory\RecipientServiceContract;
use Domain\Contracts\Repository\Directory\RecipientRepositoryContract;
use Domain\Services\AbstractService;

class RecipientService extends AbstractService  implements RecipientServiceContract
{
    private RecipientRepositoryContract $partnerRepository;
    private AccountServiceContracts $accountService;

    public function __construct(
        RecipientRepositoryContract $partnerRepository,
        AccountServiceContracts $accountService
    ){
        $this->partnerRepository = $partnerRepository;
        $this->accountService = $accountService;
        parent::__construct($partnerRepository);
    }

}
