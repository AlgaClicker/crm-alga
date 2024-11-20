<?php

namespace Domain\Services\Business;


use Auth;
use Domain\Contracts\Repository\Business\CompanyRepositoryContracts;
use Domain\Contracts\Repository\Business\CompanyBankAccountsRepositoryContracts;
use Domain\Contracts\Repository\Business\StockRepositoryContracts;
use Domain\Contracts\Services\Business\BusinessServiceContracts;
use Domain\Contracts\Repository\AccountsRepositoryContracts;
use Domain\Contracts\Services\AccountServiceContracts;
use Illuminate\Support\Facades\Log;
use Core\Exceptions\ApplicationException;
use Domain\Contracts\Services\Directory\ProfessionServiceContract;

use Domain\Contracts\Services\Crm\WorkpeopleServiceContract;


# Загрузка по INN
use MoveMoveIo\DaData\Enums\BranchType;
use MoveMoveIo\DaData\Enums\CompanyType;
use MoveMoveIo\DaData\Facades\DaDataCompany;
use Domain\Services\AbstractService;
//git flow feature finish buisnesservive

class BusinessService extends AbstractService implements BusinessServiceContracts
{
    protected CompanyRepositoryContracts $companyRepository;
    private StockRepositoryContracts $stockRepository;
    private AccountsRepositoryContracts $accountRepository;
    private AccountServiceContracts $accountService;
    private WorkpeopleServiceContract $workpeopleService;
    private ProfessionServiceContract $professionService;
    protected CompanyBankAccountsRepositoryContracts $companyBankAccountsRepository;

    public function __construct(
        CompanyRepositoryContracts $companyRepository,
        StockRepositoryContracts $stockRepository,
        AccountsRepositoryContracts $accountRepository,
        AccountServiceContracts $accountService,
        ProfessionServiceContract $professionService,
        CompanyBankAccountsRepositoryContracts $companyBankAccountsRepository
    ){
        $this->stockRepository = $stockRepository;
        $this->companyRepository = $companyRepository;
        $this->accountRepository = $accountRepository;
        $this->accountService = $accountService;
        $this->professionService = $professionService;
        $this->companyBankAccountsRepository= $companyBankAccountsRepository;

    }

    public function addCompanyBankAccount($idCompany,$account,$bankId,$bankAccount) {
        $this->repository =  $this->companyRepository;
        $company = $this->_getById($idCompany);


        //$company = $this->_getBy()
    }



    public function getAllCompany()
    {
        if ($this->accountService->getThisRole()->getService() == 'administrator' ) {
            return $this->companyRepository->findAllBy([]);
        }
            return $this->companyRepository->findAllMyCompnay();
    }

    public function getWorkPeople($options) {
        $this->repository = $this->workpeopleService;
    }

    public function registrationCompany($arrKeyValue) {

    //https://github.com/movemoveapp/laravel-dadata

        #TODO убрать зависимость от сервиса, сделать проверки на наличие в ENV
        $dadata = DaDataCompany::id(trim($arrKeyValue['inn']), 1, null, BranchType::MAIN, CompanyType::LEGAL);
        if (!$dadata['suggestions']) {
            throw new ApplicationException('Компания с ИНН ['.$arrKeyValue['inn'].'] не найдена',404);
        } else {
            $dadata = $dadata['suggestions'][0];
        }
        $compnay['name'] = $dadata['value'];
        $dadata = $dadata['data'];
        $compnay['fullname'] = $dadata['name']['full_with_opf'];

        $compnay['address'] = $dadata['address']['unrestricted_value'];
        if ($dadata['state']['status'] != "ACTIVE") {
            throw new ApplicationException('Компания с ИНН ['.$arrKeyValue['inn'].'], статус:'.$dadata['state']['status'],400);
        }


        $cols = ['kpp','inn','ogrn','okpo','okato','oktmo','okogu','ogrn_date','type'];
            foreach ($cols as $col) {
                if (array_key_exists($col,$dadata)) {
                    $compnay[$col] = $dadata[$col];
                }
            }
        $arrKeyValue['roles'] = "upravlenie";
        $newCompany =  $this->companyRepository->create($compnay);

        $arrKeyValue['company'] = $newCompany;

        $this->accountService->createRolesCompany($newCompany);
        $newAccount =  $this->accountService->registration($arrKeyValue);
        $professions = ["Директор","Бухгалтер","Менеджер снабжения","Мастер","Монтажник","Бригадир","Кадры"];

        foreach ($professions as $profession) {
            $this->professionService->create(['company'=>$newCompany->getId(),'name'=>$profession,'fullname'=>$profession,'fixed'=>true]);
        }

        //$this->professionService->create()

        $arrParamStock['name']="Основной Склад";
        $arrParamStock['fullname']="Основной Склад";
        $arrParamStock['company'] = $newCompany->getId();
        $arrParamStock['autorId'] = $newAccount->getId();
        $this->stockRepository->create($arrParamStock);



        return $newAccount;
    }

    public function createCompany($arrKeyValue)
    {
        if ($this->accountService->getThisRole()->getService() != 'administrator') {
            throw new ApplicationException('У вас нет прав на добовление компании',403);
        }
        $arrKeyValue = array_merge($arrKeyValue,[
            "autorId" => auth()->id()
        ]);
        $company = $this->companyRepository->create($arrKeyValue);



        return $company;
    }

    public function showMyCompnayStocks() {
        $stocks = $this->accountService->getAccount()->getCompany()->getStocks();

        //
        dd($stocks);
        return $this->stockRepository->findAllByCompnay(['specification'=>null]);
    }

    public function toArray() {
        return get_object_vars($this);
    }

    public function getAllStockCompany($arrKeyValue)
    {
        if (array_key_exists('stock_id',$arrKeyValue)){
            return $this->stockRepository->findAllByCompnay(['id'=>$arrKeyValue['stock_id'],'specification'=>null]);
        }
        return $this->stockRepository->listStocks();
    }

    public function removeStockMyCompany($stockId) {
        $stock = $this->stockRepository->find($stockId);

        if (!$stock) {
            abort(40,'Склад с ID ['.$stockId.'] не найден ');
        }

        $role = $this->accountService->getThisRole();
        if ($role->getService() != "upravlenie" && $role->getService() != "administrator") {
            throw new ApplicationException("У вас нет прав доступа, ваша роль:".$this->accountService->getThisRole()->getName(),403);
        }

        #TODO Проверка на объекты связанные со складом перед уаением
        if ($role->getService() == "administrator") {
            $this->stockRepository->deleteById($stockId);
            return $this->stockRepository->findAllByCompnay([]);
        }

        if ($this->accountService->getMyCompany() != $stock->getCompany()) {
            throw new ApplicationException("Склад с ID [$stockId] не найден",404);
        }


        if ($role->getService() == "upravlenie") {

            return $this->stockRepository->delete($stockId);
        }


    }
    public function editStockMyCompany($arrKeyValue)
    {
        $role = $this->accountService->getThisRole()->getService();
        if (!($role == 'upravlenie' || $role == 'administrator')) {
            throw new ApplicationException('У вас нет прав доступа, ваша роль:'.$this->accountService->getThisRole()->getName(),403);
        }

        $stock = $this->stockRepository->find($arrKeyValue['id']);
        if ($stock->getCompany() === $this->accountService->getAccount()->getCompany()) {
            return $this->stockRepository->update($stock, $arrKeyValue);
        }

        if ($role == 'administrator') {
            return $this->stockRepository->update($stock, $arrKeyValue);
        }

        throw new ApplicationException('склад с UUID ['.$arrKeyValue['id'].'] не найден',404);


    }
    public function removeCompany($idCompany)
    {

        $company = $this->companyRepository->findOne($idCompany);
        if (!$company) {
            throw new ApplicationException('Not found company id:'.$idCompany,404);
        }

        if ($this->accountService->getThisRole()->getService() != 'administrator') {
            throw new ApplicationException('У вас нет прав доступа, ваша роль:'.$this->accountService->getThisRole()->getName(),403);
        }
        $accountsCompnay = $this->accountRepository->findAllBy(['company'=>$company->getId()]);

        foreach ($accountsCompnay as $account) {
            $this->accountRepository->deleteById($account->getId());
        }

        $stocks = $this->stockRepository->findAllBy(['company'=>$company->getId()]);
        foreach ($stocks  as $stock) {
            $this->stockRepository->deleteById($stock->getId());
        }
        return $this->companyRepository->deleteById(['id'=>$idCompany]);
    }

    public function updateCompnay($idCompany,$arrKeyValue){
        $company = $this->companyRepository->findOne($idCompany);
        if ($this->accountService->getThisRole()->getService() == "upravlenie" && $this->accountService->getMyCompany() == $company ) {
            return $this->companyRepository->update($company,$arrKeyValue);
        }

        if ($this->accountService->getThisRole()->getService() == "administrator") {
            return $this->companyRepository->update($company,$arrKeyValue);
        }

        throw new ApplicationException('У вас нет прав доступа, ваша роль:'.$this->accountService->getThisRole()->getName(),403);
    }


    public function addStockCompany($arrKeyValue) {
        if (array_key_exists('company_id',$arrKeyValue) && $this->accountService->getThisRole()->getService() == "administrator") {
            #TODO Реализовать добовление по компаниии если администратор
            $stock = $this->stockRepository->create($arrKeyValue);
            return $this->stockRepository->find($stock);
        }


        if ($this->accountService->getThisRole()->getService() == "upravlenie") {
            $stock = $this->stockRepository->create($arrKeyValue);
            return $this->stockRepository->find($stock);
        }

        throw new ApplicationException('У вас нет прав доступа, ваша роль:'.$this->accountService->getThisRole()->getName(),403);
    }

    public function companyCount(){
        return $this->companyRepository->count();
    }

    public function findAllCompanyBy($arrKeyValue,$orderParam=[],$paginate=[]){
        if ($this->accountService->getThisRole()->getService() == 'administrator' ) {
            return $this->companyRepository->findAllBy([]);
        }

        unset($arrKeyValue['delete']);
        $arrKeyValue['delete'] = false;
        $newOrderParam = array();
            if ($orderParam) {
                $newOrderParam[$orderParam['nameCol']] = $orderParam['method'];
            }

        if ($this->accountService->getThisRole()->getService() != 'administrator') {
            if ($this->accountService->getThisRole()->getService() == 'upravlenie') {
                $arrKeyValue['id'] = $this->accountService->getAccount()->getCompany()->getId();

            } else {
                return [$this->accountService->getAccount()->getCompany()];
                throw new ApplicationException('У вас нет прав доступа, ваша роль:'.$this->accountService->getThisRole()->getName(),403);
            }

        }
        return [$this->accountService->getAccount()->getCompany()];

    }

    public function listAccountsCompany($idCompany) {
        $company =$this->companyRepository->findOne(['id'=>$idCompany]);
        if (!$company) {
            throw new ApplicationException('Не найдена компания с ID:'.$idCompany,404);
        }
        $accounts = $this->accountRepository->findAllBy(['company'=>$company->getId()]);
        return $accounts;
    }


    public function getListCompanyAccount($idCompany) {
        $accounts = $this->companyRepository->findAll();
        return $accounts;
    }
}
