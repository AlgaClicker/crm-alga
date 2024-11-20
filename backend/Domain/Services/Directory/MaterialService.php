<?php
namespace Domain\Services\Directory;

use Domain\Contracts\Services\Directory\MaterialServiceContract;
use Domain\Contracts\Repository\Directory\MaterialRepositoryContract;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Repository\Utils\UnitsRepositoryContracts;
use Core\Exceptions\ApplicationException;
use Domain\Entities\Business\Directory\Material;
use Domain\Services\AbstractService;
use Illuminate\Support\Collection;

class MaterialService extends AbstractService implements MaterialServiceContract
{
    private MaterialRepositoryContract $materialRepository;
    private AccountServiceContracts $accountService;
    private UnitsRepositoryContracts $unitsRepository;

    public function __construct(
        MaterialRepositoryContract $materialRepository,
        AccountServiceContracts $accountService,
        UnitsRepositoryContracts $unitsRepository
    ){
        $this->materialRepository = $materialRepository;
        $this->accountService = $accountService;
        $this->unitsRepository = $unitsRepository;
        parent::__construct($materialRepository);
    }

    public function newUnit($listUnits) {
        $role = $this->accountService->getThisRole();
        if ($role->getService() != "administrator") {
            throw new ApplicationException("У вас нет прав доступа, ваша роль:".$this->accountService->getThisRole()->getName(),403);
        }
        $newUnits = new Collection();
        foreach ($listUnits as $unit ) {

            $unitEn = $this->getUnitCode($unit['code']);

            if (!$unitEn) {
                $newUnits->push($this->unitsRepository->create($unit));
            }
        }
        return $newUnits->all();
    }

    public function _search($arrKeyValue)
    {
        if ($this->checkAttributtes($arrKeyValue) == null) {
            return null;
        }
        $arrKeyValue['isGroup'] = false;
        return parent::_search($arrKeyValue);
    }

    public function getListMaterials($parent=null,$options=[]) {
            if ($options && !array_key_exists('orderBy',$options)) {
                $options['orderBy'] = ["isGroup"=>"DESC","name"=>"DESC"];
            } else {
                unset($options['orderBy']['created_at']);
                $options['orderBy']['isGroup'] = "DESC";
            }

       return $this->materialRepository->getListMaterials($parent,$options);
    }

    public function listUnits() {
        return $this->unitsRepository->findAll();
    }

    public function getUnitCodeById($id) {
        return $this->unitsRepository->getById($id);
    }

    public function getUnitCode($code) {
        return $this->unitsRepository->findCode($code);
    }

    public function newMaterial($arrKeyValue) {
        $company = $this->accountService->getAccount()->getCompany();
        $arrKeyValue = array_merge($arrKeyValue,['company'=>$company]);
        if (array_key_exists('parent',$arrKeyValue) && $arrKeyValue['parent'] !== null){
            $parent = $this->materialRepository->findOne($arrKeyValue['parent']);

            if ($parent && $parent->getCompany() === auth()->user()->getCompany() && $parent->getIsGroup()) {
                $arrKeyValue['parent'] = $this->materialRepository->findOne($arrKeyValue['parent']);
            } else {
                unset($arrKeyValue['parent']);
            }

        }

        if ( array_key_exists('isGroup',$arrKeyValue) && $arrKeyValue['isGroup'] === true) {
            unset($arrKeyValue['code']);
            unset($arrKeyValue['articul']);
            unset($arrKeyValue['quantity']);

        }

        if (array_key_exists('unit_code',$arrKeyValue)){
           $unitCode= $arrKeyValue['unit_code'];
           unset($arrKeyValue['unit_code']);
           $until = $this->unitsRepository->findOneBy(['code'=>$unitCode]);
           $arrKeyValue['unit'] = $until;
        }

        return $this->materialRepository->create($arrKeyValue);
    }

    public function editMaterial($idMaterial,$arrKeyValue){
        $material = $this->_getById($idMaterial);

        if (array_key_exists('unit_code',$arrKeyValue)){
            $unitCode= $arrKeyValue['unit_code'];
            unset($arrKeyValue['unit_code']);
            $until = $this->unitsRepository->findOneBy(['code'=>$unitCode]);
            $arrKeyValue['unit'] = $until;
        }

        if ( array_key_exists('isGroup',$arrKeyValue) && $material->getIsGroup() != $arrKeyValue['isGroup']){
            unset($arrKeyValue['isGroup']);
        }

        if ( array_key_exists('isGroup',$arrKeyValue) && $arrKeyValue['isGroup'] == 'true') {
            unset($arrKeyValue['code']);
            unset($arrKeyValue['articul']);
        }

        if ( array_key_exists('parent',$arrKeyValue) && $arrKeyValue['parent']){

            $parent = $this->materialRepository->findOne($arrKeyValue['parent']);
            if ($parent->getCompany() === auth()->user()->getCompany() && $parent->getIsGroup()) {
                $arrKeyValue['parent'] = $this->materialRepository->findOne($arrKeyValue['parent']);
            } else {
                unset($arrKeyValue['parent']);
            }
        }

        return $this->materialRepository->update($idMaterial,$arrKeyValue);
    }

    public function deleteMaterial($uuidMaterial) {
        $role = $this->accountService->getThisRole();
        if ($role->getService() != "upravlenie" && $role->getService() != "administrator"  && $role->getService() != "snabzenie") {
            throw new ApplicationException("У вас нет прав доступа, ваша роль:".$this->accountService->getThisRole()->getName(),403);
        }

        $material = $this->materialRepository->findByUuid($uuidMaterial);
        if (!$material || $material->getCompany() !== auth()->user()->getCompany()) {
            throw new ApplicationException("Материал с UUID: [$uuidMaterial] не найден.",404);
        }


        if ($material->getSpecificationmaterial()->count() > 0 ) {
            $spec = $material->getSpecificationmaterial()->first();
            throw new ApplicationException("Материал с UUID: [$uuidMaterial] имеет связь сматериалом спецификации ID:".$spec->getId(),400);
        }

        if ($material && $this->materialRepository->findAllParent($uuidMaterial) && $material->getIsGroup()) {
            throw new ApplicationException("Группа материалов c UUID: [$uuidMaterial] содержит элементы, удалите дочерние материалы.",400);
        }

        if ($material->getRequisitionInvoiceMaterial()->count() > 0) {
            abort(400,'Материал используется в заявках, удаление невозможно');
        }

        if ($material) {
            return $this->materialRepository->delete($material);
        }

        throw new ApplicationException("Материал с UUID: [$uuidMaterial] не найден.",404);

    }

    public function historyMaterial($uuidMaterial) {
        return $this->materialRepository->history($uuidMaterial);
    }


    public function showMaterialCategory($id=null,$parent = null) {
        //dd($id);
        if ($this->getById($id)) {
            return $this->getById($id);
        }
        if ($parent) {
           if (!$this->materialRepository->findByUuid($parent)->getIsGroup()) {
               throw new ApplicationException("Материал c UUID: [$parent] не является группой",400);
           }
        }
        return $this->materialRepository->findAllParent($parent);
    }

    public function getById($idMaterial) {
        $company = $this->accountService->getAccount()->getCompany();
        return $this->materialRepository->findBy(['id'=>$idMaterial,'company'=>$company]);
    }


    public function searchBy($arrKeyValue, $orderBy=null) {
        return $this->materialRepository->searchBy($arrKeyValue, $orderBy);
    }

    public function getByName($nameMaterial) {
        $company = $this->accountService->getAccount()->getCompany();
        return $this->materialRepository->searchByName($nameMaterial);
    }

    public function getByArtucul($articulMaterial) {
        //articul

    }

}
