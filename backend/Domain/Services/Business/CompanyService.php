<?php

namespace Domain\Services\Business;
use Domain\Services\AbstractService;
use Domain\Contracts\Services\Business\CompanyServiceContract;

use Domain\Contracts\Repository\Business\CompanyRepositoryContracts;
use Domain\Contracts\Repository\Business\CompanyBankAccountsRepositoryContracts;
use Domain\Contracts\Services\Directory\BankServiceContract;


class CompanyService extends AbstractService implements CompanyServiceContract
{
    protected CompanyRepositoryContracts $companyRepository;
    protected CompanyBankAccountsRepositoryContracts $companyBankAccountsRepository;
    private BankServiceContract $bankService;

    public function __construct(
        CompanyRepositoryContracts $companyRepository,
        CompanyBankAccountsRepositoryContracts $companyBankAccountsRepository,
        BankServiceContract $bankService
    ){
        $this->companyRepository = $companyRepository;
        $this->companyBankAccountsRepository = $companyBankAccountsRepository;
        $this->bankService = $bankService;
        parent::__construct($companyRepository);
    }

    public function getMyCompany() {
        return auth()->user()->getCompany();
    }
    public function getBankAccountsCompany($idCompany) {
        //dd($this->_getById($idCompany));
    }

    public function checkBankAccountCompany($bankAccount) {

        return $this->companyBankAccountsRepository->findByMyCompnay(['account'=>$bankAccount]);
    }

    public function createBankAccountCompany(string $bik,$bankAccount) {

        $bank = $this->bankService->getBankBik($bik);

        $accountCompany = $this->companyBankAccountsRepository->findByMyCompnay(['bank'=>$bank,'account'=>$bankAccount]);
        if (!$accountCompany && $bank) {
            return $this->companyBankAccountsRepository->create(['bank'=>$bank,'account'=>$bankAccount]);

        } else {
            return $accountCompany;
        }

    }
}
