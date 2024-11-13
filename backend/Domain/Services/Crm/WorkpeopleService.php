<?php

namespace Domain\Services\Crm;

use Domain\Entities\Business\Company\Workpeople;
use Domain\Services\AbstractService;
use Core\Exceptions\ApplicationException;
use Domain\Contracts\Services\Crm\WorkpeopleServiceContract;
use Domain\Contracts\Repository\Crm\WorkpeopleRepositoryContract;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Domain\Contracts\Repository\Crm\TimesheetRepositoryContracts;
use Domain\Contracts\Services\Directory\ProfessionServiceContract;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;

#TODO ================================
#TODO НАстройить права доступа

class WorkpeopleService extends AbstractService implements WorkpeopleServiceContract
{
    private WorkpeopleRepositoryContract $workpeopleRepository;
    protected AccountServiceContracts $accountService;
    private  TimesheetRepositoryContracts $timesheetRepository;
    private SpecificationsServiceContracts $specificationsService;
    protected ProfessionServiceContract $professionService;

    public function __construct(
        WorkpeopleRepositoryContract $workpeopleRepository,
        AccountServiceContracts $accountService,
        TimesheetRepositoryContracts $timesheetRepository,
        SpecificationsServiceContracts $specificationsService,
        ProfessionServiceContract $professionService
    ) {
        $this->workpeopleRepository = $workpeopleRepository;
        $this->accountService = $accountService;
        $this->timesheetRepository = $timesheetRepository;
        $this->specificationsService = $specificationsService;
        $this->professionService = $professionService;
        parent::__construct($workpeopleRepository);
    }

    public function setTimeSheet(
        $workpeople,
        \DateTimeImmutable $date,
        $timeAmount,
        $timeExtra='',
        $specificationId='',
        $description=''
    ) {


        //$this->timesheetRepository
        $people = $this->_getById($workpeople);

        if (!$people) {
            throw new ApplicationException("Пользователь c ID ".$workpeople." не найден");
        }
        $array['date'] = $date;
        $array['workpeople'] = $people;


        $searchRecord = $this->timesheetRepository->findAllByCompnay($array);

        if ($description) {
            $array['description'] = $description;
        }

        if ($timeExtra) {
            $array['timeExtra'] = $timeExtra;
        }

        if ($specificationId) {
            $spec = $this->specificationsService->_getById($specificationId);
            if ($spec) {
                $array['specification'] = $spec;
            }
        }

        if ($searchRecord) {
            throw new ApplicationException("У пользователя c ID ".$people->getId()." на дату ".$date->format('d-m-Y')." уже имеется запись",400);

        } else {
            $array['timeAmount'] = $timeAmount;

            return $this->timesheetRepository->create($array);
        }
    }

    public function listTimeSheet($idWorkpeople, $month,$year) {
        $people = $this->_getById($idWorkpeople);
        return $this->timesheetRepository->listTimeSheet($people,$month,$year);
    }



    public function editTimeSheet(
        $workpeople,
        \DateTimeImmutable $date,
        $timeAmount,
        $timeExtra='',
        $specificationId='',
        $description=''
    ) {
        $people = $this->_getById($workpeople);
        if (!$people) {
            throw new ApplicationException("Пользователь c ID ".$workpeople." не найден");
        }
        $array['date'] = $date;
        $array['workpeople'] = $people;
        $searchRecord = $this->timesheetRepository->findByMyCompnay($array);
        if (!$searchRecord) {
            throw new ApplicationException("Пользователь c ID ".$workpeople." не найдена запись на указанную дату");
        }
        if ($description) {
            $array['description'] = $description;
        }
        if ($timeAmount) {
            $array['timeAmount'] = $timeAmount;
        }

        if ($timeExtra) {
            $array['timeExtra'] = $timeExtra;
        }
        unset( $array['date']);
        if ($specificationId) {
            $spec = $this->specificationsService->_getById($specificationId);
            if ($spec) {
                $array['specification'] = $spec;
            }
        }
        return $this->timesheetRepository->update($searchRecord,$array);
    }

    public function getAccountWorkPeople($workpeople) {
        if ($this->_getById($workpeople)) {
            return $this->_getById($workpeople)->getAccount();
        }
    }

    public function deleteTimeSheet($idWorkpeople, \DateTimeImmutable $date) {
        $array['date'] = $date;
        $people = $this->_getById($idWorkpeople);
        $array['workpeople'] = $people;
        $searchRecord = $this->timesheetRepository->findByMyCompnay($array);

        if (!$searchRecord) {
            throw new ApplicationException("У пользователя c ID ".$people->getId()." на дату ".$date->format('d-m-Y')." не найдена запись",400);
        }

        return $this->timesheetRepository->deleteById($searchRecord);
    }

    public function _create($arrKeyValue)
    {
        unset($arrKeyValue['master']);
        if (array_key_exists('account',$arrKeyValue)) {
            if ($arrKeyValue['account'] && array_key_exists('id',$arrKeyValue['account'])) {
                $arrKeyValue['account'] = $arrKeyValue['account']['id'];
            }
        }

        if (array_key_exists("brithAt",$arrKeyValue)) {
            $arrKeyValue['brithAt'] = new \DateTimeImmutable($arrKeyValue['brithAt']);
        }
        if (array_key_exists("brith_at",$arrKeyValue)) {
            $arrKeyValue['brithAt'] = new \DateTimeImmutable($arrKeyValue['brith_at']);
        }

        if (array_key_exists("phone_number",$arrKeyValue)) {
            $arrKeyValue['phoneNumber'] = trim($arrKeyValue['phone_number']);
        }
        if (array_key_exists("tabel_number",$arrKeyValue)) {
            $arrKeyValue['tabelNumber'] = trim($arrKeyValue['tabel_number']);
        }

        $arrKeyValue['master'] = auth()->user();
        if (array_key_exists("inn",$arrKeyValue)) {
            $arrKeyValue['inn'] = trim($arrKeyValue['inn']);
        }

        return parent::_create($arrKeyValue);
    }
    public function getListWorkpeople($options = []): array
    {


        if ($this->account->getRoles()->getService() == "upravlenie") {
                return $this->_getAllBy([],$options);
        }
        return $this->workpeopleRepository->getListWorkpeople($options);

    }

    public function _getById($idWorkpeople)
    {
        $workpeople = parent::_getById($idWorkpeople);

        if (!$workpeople) {
            abort(404,"Сотрудник с Id [$idWorkpeople] не найден");
        }

        if ($this->account->getRoles()->getService() == "upravlenie") {
            return $workpeople;
        }

        if ($workpeople && ($workpeople->getAutorId() == $this->account->getId() && $workpeople->getAutor() == $this->account )) {
            return  $workpeople;
        }


        if ($workpeople && $workpeople->getMaster() == $this->account) {
            return  $workpeople;
        }

        abort(404,"Сотрудник с Id [$idWorkpeople] не найден");

    }
    public function _updateById($id, $arrKeyValue)
    {
        unset($arrKeyValue['master']);
        if (array_key_exists('account',$arrKeyValue) && is_array($arrKeyValue['account'])) {
            if (array_key_exists('id',$arrKeyValue['account'])) {
                $arrKeyValue['account'] = $arrKeyValue['account']['id'];
            }
        } elseif (array_key_exists('account',$arrKeyValue) && !$arrKeyValue['account']) {
            $arrKeyValue['account'] = null;
        }

        $workpeople = parent::_getById($id);

        if ($this->account->getRoles()->getService() == "upravlenie") {
            return parent::_updateById($id, $arrKeyValue);
        }

        if ($workpeople->getAutor()->getId() === $this->account->getId()) {
            return parent::_updateById($id, $arrKeyValue);
        }

        abort(403,'Ошибка допуспа к записи');
    }

    public function setMaster($idWorkpeople,$idMaster)
    {

        if ($this->account->getRoles()->getService() != "upravlenie") {
            Log::info("Попытка установки мастера пользователем [".$this->account->getUsername()."] сотруднику с ID $idWorkpeople");
            return parent::_getById($idWorkpeople);
        }
        $workpeople = parent::_getById($idWorkpeople);
        $workpeople1 = new Workpeople();
        $master = $this->accountService->getAccountMyCompnay($idMaster);
        $workpeople = $this->workpeopleRepository->update($workpeople,['master'=>$master]);
        $title = "Установка сотруднику [".$workpeople->getSurname()." ".$workpeople->getName()." ".$workpeople->getPatronymic()."] мастера:".$master->getUsername();
        $this->sendSystemNotification("Отве́тственный за сотрудника",$title);
        return $workpeople;

    }
}
