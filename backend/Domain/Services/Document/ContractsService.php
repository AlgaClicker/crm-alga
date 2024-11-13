<?php
namespace Domain\Services\Document;
use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Domain\Contracts\Services\Directory\PartnersServiceContract;
use Domain\Services\AbstractService;
use Core\Exceptions\ApplicationException;

use Domain\Contracts\Services\Document\ContractsServiceContract;

use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\FileServiceContracts;
use Domain\Contracts\Repository\Document\ContractsRepositoryContract;

class ContractsService extends AbstractService implements ContractsServiceContract
{
    protected ContractsRepositoryContract $repositoryContract;
    protected FileServiceContracts $fileService;
    protected SpecificationsServiceContracts $specificationsService;

    protected PartnersServiceContract $partnersService;

    public function __construct(
        ContractsRepositoryContract $repositoryContract,
        FileServiceContracts $fileService,
        SpecificationsServiceContracts $specificationsService,
        PartnersServiceContract $partnersService
    ) {
        $this->repositoryContract = $repositoryContract;
        $this->specificationsService = $specificationsService;
        $this->partnersService = $partnersService;
        $this->fileService = $fileService;
        parent::__construct($this->repositoryContract);
    }


    public function getListContracts($options) {

        return $this->repositoryContract->getListContracts($options);
    }
    public function getListSupplyContracts($options)
    {
        return $this->repositoryContract->getListSupplyContracts($options);
    }
    public function getListWorkContracts($options) {

        return $this->repositoryContract->getListWorkContracts($options);
    }
    public function addSupplyContract($arrKeyVal) {

        if (array_key_exists('specifications',$arrKeyVal) && is_array($arrKeyVal['specifications'])) {
            $specifications = $arrKeyVal['specifications'];
            unset($arrKeyVal['specifications']);
            foreach ($specifications as $specificationArr) {
                if (is_array($specificationArr) && array_key_exists('id', $specificationArr)) {
                    $specification =  $this->specificationsService->getSpecificationOnly($specificationArr['id']);
                    if ($specification) {
                        $arrKeyVal['specifications'][] = $specification;
                    }
                }

            }
        }


        $arrKeyVal['partner'] = $this->partnersService->_getById($arrKeyVal['partner']['id']);
        if (!$arrKeyVal['partner']) {
            abort('404','Контрагент не найден');
        }

        if (array_key_exists('files',$arrKeyVal) && is_array($arrKeyVal['files'])) {
            $files = [];
            foreach ($arrKeyVal['files'] as $file_hash) {
                $file = $this->fileService->getHashFile($file_hash);
                if ($file) {
                    $files[] = $file;
                }
            }

            $arrKeyVal['files'] = $files;

        }

        return $this->repositoryContract->addSupplyContract($arrKeyVal);

    }

    public function remove($idContract)
    {
        $contract = $this->_getById($idContract);

        if (!$contract) {
            abort('404','Договор не найден');
        }

        if($contract->getFixed() === true) {
            abort('404','Фиксированный договор. удалене невозможно');
        }

        return $this->repositoryContract->deleteByUuid($contract->getId());
    }


    public function addСonstructionContract($arrKeyVal) {

        if (array_key_exists('specifications',$arrKeyVal) && is_array($arrKeyVal['specifications'])) {
            $specifications = $arrKeyVal['specifications'];
            unset($arrKeyVal['specifications']);
            foreach ($specifications as $specificationArr) {
                if (is_array($specificationArr) && array_key_exists('id', $specificationArr)) {
                    $specification =  $this->specificationsService->getSpecificationOnly($specificationArr['id']);
                    if ($specification) {
                        $arrKeyVal['specifications'][] = $specification;
                    }
                }

            }
        }

        if (array_key_exists('partner',$arrKeyVal) && array_key_exists('id',$arrKeyVal['partner'])) {
            $partner_id = $arrKeyVal['partner']['id'];
            $partner = $this->partnersService->_getById($partner_id);
            unset($arrKeyVal['partner']);
            if (!$partner) {
                abort('404','Контрагент не найден');
            }
            $arrKeyVal['partner'] = $this->partnersService->_getById($partner_id);
        } else {
            abort('404','Контрагент не найден');
        }

        if (is_array($arrKeyVal) && array_key_exists('files',$arrKeyVal) && is_array($arrKeyVal['files'])) {
            $files = [];
            foreach ($arrKeyVal['files'] as $file_hash) {
                $file = $this->fileService->getHashFile($file_hash);
                if ($file) {
                    $files[] = $file;
                }
            }
            $arrKeyVal['files'] = $files;
        }

        return $this->repositoryContract->addСonstructionContract($arrKeyVal);
    }

    public function _updateById($id, $arrKeyValue)
    {
        if (array_key_exists('credit_at_days',$arrKeyValue)) {
            $arrKeyValue['credit_days'] = intval($arrKeyValue['credit_at_days']);
            $arrKeyValue['credit'] = intval($arrKeyValue['credit_amount']);
        }

        if (array_key_exists('partner',$arrKeyValue) && array_key_exists('id',$arrKeyValue['partner'])) {
            $partner_id = $arrKeyValue['partner']['id'];
            $partner = $this->partnersService->_getById($partner_id);
            unset($arrKeyValue['partner']);
            if (!$partner) {
                abort('404','Контрагент не найден');
            }
            $arrKeyValue['partner'] = $this->partnersService->_getById($partner_id);
        }

        return parent::_updateById($id, $arrKeyValue); // TODO: Change the autogenerated stub
    }

    public function addSpecificationContract($idContract, $idSpecification)
    {
        $contract = $this->_getById($idContract);
        $specification = $this->specificationsService->getSpecificationOnly($idSpecification);
        return $this->repositoryContract->addSpecificationContract($contract, $specification);
    }
    public function removeSpecificationContract($contractId,$specificationId)
    {
        $contract = $this->_getById($contractId);
        $specification = $this->specificationsService->getSpecificationOnly($specificationId);
        return $this->repositoryContract->removeSpecificationContract($contract, $specification);
    }

    public function listSpecificationContract($contractId,$options=[]) {
        $contract = $this->_getById($contractId);
        return $this->repositoryContract->listSpecificationContract($contract,$options);
    }
}
