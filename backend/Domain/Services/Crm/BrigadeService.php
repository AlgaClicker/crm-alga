<?php

namespace Domain\Services\Crm;

use Domain\Entities\Business\Master\Brigade;
use Domain\Services\AbstractService;
use Core\Exceptions\ApplicationException;
use Domain\Contracts\Services\Crm\BrigadeServiceContract;
use Domain\Contracts\Repository\Crm\BrigadeRepositoryContracts;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Crm\WorkpeopleServiceContract;
use Domain\Contracts\Repository\Crm\TimesheetRepositoryContracts;
use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Domain\Contracts\Services\FileServiceContracts;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class BrigadeService extends AbstractService implements BrigadeServiceContract
{
    protected BrigadeRepositoryContracts $brigadeRepository;
    protected AccountServiceContracts $accountService;
    protected WorkpeopleServiceContract $workpeopleService;
    protected TimesheetRepositoryContracts $timesheetRepository;
    protected SpecificationsServiceContracts $specificationsService;
    protected FileServiceContracts $filesService;

    public function __construct(
        BrigadeRepositoryContracts $brigadeRepository,
        AccountServiceContracts $accountService,
        WorkpeopleServiceContract $workpeopleService,
        TimesheetRepositoryContracts $timesheetRepository,
        SpecificationsServiceContracts $specificationsService,
        FileServiceContracts $filesService
    )
    {
        $this->brigadeRepository = $brigadeRepository;
        $this->accountService = $accountService;
        $this->workpeopleService = $workpeopleService;
        $this->timesheetRepository = $timesheetRepository;
        $this->specificationsService = $specificationsService;
        $this->filesService = $filesService;
        parent::__construct($brigadeRepository);
    }

    public function getAllBy($arrKeyValue, $options = [])
    {
        $full_access_role = ['upravlenie', 'administrator'];
        if (in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            return parent::_getAllBy($arrKeyValue, $options);
        }

        if ($this->accountService->getThisRole()->getService() == "master") {
            $arrKeyValue['master'] = $this->accountService->getAccount();
            return parent::_getAllBy($arrKeyValue, $options);
        }

        return ['data'=>null,'options'=>null];
    }

    public function deletePeople($idPeople) {
        return $this->workpeopleService->_delete($idPeople);
    }

    public function _delete($idEntity)
    {
        $brigade = $this->brigadeRepository->findMyCompnay($idEntity);

        if ($brigade->getWorkpeoples()->count() != 0) {
            throw new ApplicationException("В бригаде |".$brigade->getName()."| имеются сотрудники", 400);
        }
        return parent::_delete($idEntity);
    }

    public function _updateById($id, $arrKeyValue)
    {
        $brigade = parent::_getById($id);
        $full_access_role = ['upravlenie', 'administrator'];

        //Str::isUuid
        if (array_key_exists('master', $arrKeyValue) && !Str::isUuid($arrKeyValue['master'])) {
            if (gettype($arrKeyValue['master']) == 'array' && array_key_exists('id', $arrKeyValue['master'])) {
                $arrKeyValue['master'] = $arrKeyValue['master']['id'];
            }
        } elseif (array_key_exists('master', $arrKeyValue) && is_array($arrKeyValue['master']) && $arrKeyValue['master'] && array_key_exists('id', $arrKeyValue['master']) && Str::isUuid($arrKeyValue['master']['id'])) {
            $arrKeyValue['master'] = $arrKeyValue['master']['id'];
        }


        if (array_key_exists('masterId', $arrKeyValue)) {
            $master = $this->accountService->getOne($arrKeyValue['masterId']);
            if ($master && $master->getCompany() == $this->accountService->getMyCompany()) {
                $arrKeyValue['master'] = $master;
            }
        }

        if (in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            return parent::_updateById($id, $arrKeyValue);
        }

        if ($brigade && $brigade->getMaster() == $this->accountService->getAccount()) {
            return parent::_updateById($id, $arrKeyValue);
        }

        return $brigade;

    }

    public function delete($idBrigade) {
        $full_access_role = ['upravlenie', 'administrator', 'master','kadry'];
        if (!in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            throw new ApplicationException("У вас нет прав на создание, ваша роль:" . $this->accountService->getThisRole()->getName(), 403);
        }
        $brigade = $this->_getById($idBrigade);

        if ($this->accountService->getThisRole()->getService() == "master") {
            if ($brigade->getMaster() == $this->accountService->getAccount()) {
                return $this->brigadeRepository->deleteById($brigade);
            }
        }

        if ($this->accountService->getThisRole()->getService() == "upravlenie") {
                return $this->brigadeRepository->deleteById($brigade);
        }

        return $brigade;
    }


    public function create($arrKeyValue)
    {
        $full_access_role = ['upravlenie', 'administrator', 'master','kadry'];
        if (!in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            throw new ApplicationException("У вас нет прав на создание, ваша роль:" . $this->accountService->getThisRole()->getName(), 403);
        }

        if (array_key_exists("idMaster", $arrKeyValue)) {

            $master = $this->accountService->getOne($arrKeyValue['idMaster']);
            unset($arrKeyValue['idMaster']);

            if ($master && $master->getCompany() == $this->accountService->getMyCompany()) {
                $arrKeyValue['master'] = $master;
            }
        }
        if ($this->accountService->getThisRole()->getService() == "master") {
            $arrKeyValue['master'] = $this->accountService->getAccount();
        }

        return parent::_create($arrKeyValue);
    }

    public function createPeople($arrKeyValue)
    {
        $brigade = parent::_getById($arrKeyValue['idBrigade']);
        $full_access_role = ['upravlenie', 'administrator'];
        $arrKeyValue['brigade'] = $brigade->getId();

        if (in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            return $this->workpeopleService->_create($arrKeyValue);
        }

        if ($this->accountService->getThisRole()->getService() == "master") {
            if ($brigade->getMaster() == $this->accountService->getAccount()) {
                return $this->workpeopleService->_create($arrKeyValue);
            } else {
                throw new ApplicationException("У вас нет прав для доступа к бригаде с UUID [" . $brigade->getId() . "], ваша роль:" . $this->accountService->getThisRole()->getName(), 403);
            }
        }
        throw new ApplicationException("У вас нет прав, ваша роль:" . $this->accountService->getThisRole()->getName(), 403);

    }

    public function updatePeople($arrKeyValue)
    {

        $people = $this->workpeopleService->_getById($arrKeyValue['id']);
        $brigade = $this->_getById($arrKeyValue['idBrigade']);



        if ($this->accountService->getThisRole()->getService() == "master") {
            if ($brigade && $brigade->getMaster() == $this->accountService->getAccount()) {

            }
        }
        $full_access_role = ['upravlenie', 'administrator'];
        if ($brigade && in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            $arrKeyValue['brigade'] = $brigade->getId();

            return $this->workpeopleService->_updateById($people, $arrKeyValue);
        }

        return  $brigade;
    }

    public function listPeopleBrigade($idBrigade,$arrKeyValue=[])
    {
        $peopleBrigade = null;
        $brigade = parent::_getById($idBrigade);

        if (!$brigade) {
            return ['data' => null, 'options' => null];
        }

        $full_access_role = ['upravlenie', 'administrator'];
        $arrKeyValue['brigade'] = $brigade->getId();
        if (in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            if (array_key_exists('options', $arrKeyValue)) {
                $peopleBrigade = $this->workpeopleService->_getAllBy(['brigade' => $brigade], $arrKeyValue['options']);
            } else {
                $peopleBrigade = $this->workpeopleService->_getAllBy(['brigade' => $brigade]);
            }

        }

        if ($this->accountService->getThisRole()->getService() == "master") {
            $master = $this->accountService->getAccount();
            if ($brigade->getMaster() != $master) {
                throw new ApplicationException("У вас нет прав для доступа к бригаде с UUID [" . $brigade->getId() . "], ваша роль:" . $this->accountService->getThisRole()->getName(), 403);
            }
            if (array_key_exists('options', $arrKeyValue)) {
                $peopleBrigade = $this->workpeopleService->_getAllBy(['brigade' =>$idBrigade], $arrKeyValue['options']);
            } else {
                $peopleBrigade = $this->workpeopleService->_getAllBy(['brigade' => $idBrigade]);
            }

        }

        return $peopleBrigade;
    }

    public function setTimeSheet($workpeople, \DateTimeImmutable $date, $timeAmount) {
       return $this->workpeopleService->setTimeSheet($workpeople,  $date, $timeAmount);
    }

    public function listTimeSheet($idWorkpeople, $month,$year) {
        return $this->workpeopleService->listTimeSheet($idWorkpeople, $month,$year);
    }


    public function addPeopleBrigade($idBrigade,$idPeople) {
        $full_access_role = ['upravlenie', 'administrator', 'master'];
        if (!in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            throw new ApplicationException("У вас нет прав на создание, ваша роль:" . $this->accountService->getThisRole()->getName(), 403);
        }

        $brigade = $this->_getById($idBrigade);
        if (!$brigade) {
            throw new ApplicationException("Бригада не найдена:", 404);
        }

        $people = $this->workpeopleService->_getById($idPeople);
        if (!$people) {
            throw new ApplicationException("Сотрудник не найден:", 404);
        }

        if ($brigade->getWorkpeoples()->contains($people)) {
            throw new ApplicationException("Сотрудник [$idPeople] найден в бригаде [$idBrigade]", 400);
        }

        if ($people->getBrigade()->count()>0) {
            dd($people->getBrigade()->count());
            throw new ApplicationException("Сотрудник [$idPeople] приявзан к бригаде [".$people->getBrigade()->first()."]", 400);
        }

        return $this->workpeopleService->_updateById($people->getId(),['brigade'=>$brigade]);
    }

    public function removePeopleBrigade($idBrigade,$idPeople) {
        $full_access_role = ['upravlenie', 'administrator', 'master'];
        if (!in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            throw new ApplicationException("У вас нет прав на создание, ваша роль:" . $this->accountService->getThisRole()->getName(), 403);
        }

        $brigade = $this->_getById($idBrigade);
        if (!$brigade) {
            throw new ApplicationException("Бригада не найдена:", 404);
        }

        $people = $this->workpeopleService->_getById($idPeople);
        if (!$people) {
            throw new ApplicationException("Сотрудник не найден:", 404);
        }

        return $this->workpeopleService->_updateById($people->getId(),['brigade'=>'']);

    }

    public function deleteSpecificationsBrigade($idBrigade,$idDocSpecificationsBrigade) {
        $full_access_role = ['upravlenie', 'administrator', 'master'];
        if (!in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            throw new ApplicationException("У вас нет прав на создание, ваша роль:" . $this->accountService->getThisRole()->getName(), 403);
        }

        return $this->brigadeRepository->deleteSpecificationsBrigade($idBrigade,$idDocSpecificationsBrigade);




    }

    public function movePeopleBrigade($idBrigade, $idPeople, $idNewBrigade) {
        if ($idBrigade === $idNewBrigade) {
            throw new ApplicationException("Перенос из бригады [$idBrigade] в бригаду [$idNewBrigade] невозможен", 400);
        }

        $full_access_role = ['upravlenie', 'administrator', 'master'];
        if (!in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            throw new ApplicationException("У вас нет прав на создание, ваша роль:" . $this->accountService->getThisRole()->getName(), 403);
        }

        $brigade = $this->_getById($idBrigade);
        if (!$brigade) {
            throw new ApplicationException("Бригада [$idBrigade] не найдена", 404);
        }

        $people = $this->workpeopleService->_getById($idPeople);
        if (!$people) {
            throw new ApplicationException("Сотрудник [$idPeople] не найден", 404);
        }

        $newBrigade = $this->_getById($idNewBrigade);
        if (!$newBrigade) {
            throw new ApplicationException("Бригада [$idNewBrigade] не найдена", 404);
        }

        return $this->workpeopleService->_updateById($people->getId(),['brigade'=>$newBrigade]);
    }


    public function listSpecificationsBrigade($id,$options=[]) {
        return $this->brigadeRepository->listSpecificationActual($id);
    }

    public function listPeopleBrigadeFree($options=[]) {
        $full_access_role = ['upravlenie','administrator'];

        if (in_array($this->accountService->getThisRole()->getService(), $full_access_role)) {
            return $this->workpeopleService->_getAllBy(['brigade'=>null],$options);
        }
        return $this->workpeopleService->_getAllBy(['brigade'=>null,'autor'=>$this->account->getId()],$options);

    }

    public function setSpecificationsBrigade($brigade_id,$specification_id,$date_end) {
        $brigade = $this->_getById($brigade_id);
        $specification = $this->specificationsService->getSpecificationOnly($specification_id);

        return $this->brigadeRepository->setSpecificationDateEndWork($specification->getId(),$brigade->getId(),$date_end);


    }
}
