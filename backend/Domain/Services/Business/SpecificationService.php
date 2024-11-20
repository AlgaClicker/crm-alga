<?php
namespace Domain\Services\Business;
use Auth;
use Core\Exceptions\ApplicationException;
use Doctrine\ORM\Cache\CollectionCacheEntry;
use Domain\Contracts\Services\NotificationServiceContracts;
use Domain\Entities\Business\Directory\Material;
use Domain\Entities\Business\Objects\Specification;
use Domain\Entities\Subscriber\Account;
use Domain\Services\AbstractService;
use GPBMetadata\Google\Api\Log;
use http\Client\Curl\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidFactory;
use Ramsey\Uuid\Type\Integer;
use function Termwind\terminal;

use Domain\Contracts\Repository\Business\CompanyRepositoryContracts;
use Domain\Contracts\Repository\Business\StockRepositoryContracts;
use Domain\Contracts\Repository\Crm\SpecificationRepositoryContracts;
use Domain\Contracts\Repository\Crm\SpecificationMaterialRepositoryContracts;
use Domain\Contracts\Repository\Crm\SpecificationResourcesRepositoryContracts;
use Domain\Contracts\Repository\Crm\SpecificationTypeWorksRepositoryContracts;

use Domain\Contracts\Services\Crm\ObjectsServiceContracts;
use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\FileServiceContracts;
use Domain\Contracts\Services\Directory\MaterialServiceContract;


use PhpOffice\PhpSpreadsheet\IOFactory;

/**  Entity  **/

use Domain\Entities\Business\Objects\Objects;
use Domain\Contracts\Repository\Crm\Specification\MaterialCalculationRepositoryContracts;
use Domain\Contracts\Repository\Payments\InvoicesRepositoryContract;
use Domain\Contracts\Services\Document\ContractsServiceContract;

#git flow feature finish buisnesservive

class SpecificationService extends AbstractService implements SpecificationsServiceContracts
{
    protected CompanyRepositoryContracts $companyRepository;
    protected StockRepositoryContracts $stockRepository;
    protected AccountServiceContracts $accountService;
    protected SpecificationRepositoryContracts $specificationRepository;
    protected FileServiceContracts $fileService;
    protected MaterialServiceContract $materialService;
    protected ObjectsServiceContracts $objectService;
    protected SpecificationMaterialRepositoryContracts $specificationMaterialRepository;
    protected SpecificationResourcesRepositoryContracts $specificationResourcesRepository;
    protected SpecificationTypeWorksRepositoryContracts $specificationTypeWorksRepository;
    protected MaterialCalculationRepositoryContracts $materialCalculationRepository;
    private InvoicesRepositoryContract $invoicesRepository;
    protected NotificationServiceContracts $notificationService;


    public function __construct(
        CompanyRepositoryContracts $companyRepository,
        StockRepositoryContracts $stockRepository,
        AccountServiceContracts $accountService,
        SpecificationRepositoryContracts $specificationRepository,
        FileServiceContracts $fileService,
        ObjectsServiceContracts $objectService,
        SpecificationMaterialRepositoryContracts $specificationMaterialRepository,
        MaterialServiceContract $materialService,
        SpecificationResourcesRepositoryContracts $specificationResourcesRepository,
        SpecificationTypeWorksRepositoryContracts $specificationTypeWorksRepository,
        MaterialCalculationRepositoryContracts $materialCalculationRepository,
        InvoicesRepositoryContract $invoicesRepository,
        NotificationServiceContracts $notificationService

    ){
        $this->stockRepository = $stockRepository;
        $this->companyRepository = $companyRepository;
        $this->accountService = $accountService;
        $this->specificationRepository = $specificationRepository;
        $this->fileService = $fileService;
        $this->materialService = $materialService;
        $this->objectService = $objectService;
        $this->specificationMaterialRepository = $specificationMaterialRepository;
        $this->specificationResourcesRepository = $specificationResourcesRepository;
        $this->specificationTypeWorksRepository = $specificationTypeWorksRepository;
        $this->materialCalculationRepository= $materialCalculationRepository;
        $this->invoicesRepository=$invoicesRepository;
        $this->notificationService = $notificationService;


        parent::__construct($specificationRepository);
    }


    public function listSpec($objectId=null,$specificationId=null, $options=[]) {
        $object = "";
        if ($objectId) {
            $object = $this->objectService->getObject($objectId);
        }

        $account = $this->accountService->getAccount();

        $role = $account->getRoles()->getService();


        if ($object &&  ($role == 'upravlenie' || $role == 'administrator')) {
           // return $this->_getAllBy(['object'=>$object,'parent'=>null],$options);
        }

        if ($object &&  ($role == 'upravlenie' || $role == 'snabzenie')) {
            $listSpec = new Collection();
            $listObjects  = $this->objectService->listObjects()['data'];


            foreach ($listObjects as $object) {
                $specifications = $object->getSpecification();
                foreach ($specifications as $specification) {

                    if (!$specification->getResponsibles()->contains($account) && $specification->getFixed() === true) {
                        $listSpec->add($specification);
                    }
                }

            }

            return  ['data'=>$listSpec->all(),'options'=>$options];
        }

        if ($object && !$specificationId) {
            return $this->_getAllBy(['object'=>$object,'parent'=>null],$options);

        }

        if ($specificationId) {

            return $this->specificationRepository->findMyCompnay($specificationId);
        }

        return  ['data'=>'','options'=>$options];

    }

    public function getSpecificationListMy() {
        return $this->specificationRepository->listSpecificationsResponsible(auth()->user());
    }

    public function getSpecificationListMaster(Account $account) {

            return $this->specificationRepository->listSpecificationsResponsible($account);
    }


    public function actualSpecificationObject($objectId,$option=[]) {
        $object = $this->objectService->getObject($objectId);
        return $this->specificationRepository->listSpecificationsByObject($object,$option);
    }

    public function specificationAll($options=[]) {

        if ($this->accountService->getAccount()->getRoles()->getService() == "administrator") {
            return $this->specificationRepository->listSpecificationsAllFixed($options);
        }

        if ($this->accountService->getAccount()->getRoles()->getService() == "upravlenie") {

            return $this->specificationRepository->listSpecificationsAllFixed($options);
        }

        return $this->specificationRepository->listSpecificationsResponsible(auth()->user());


        $list = new Collection();
        $listSpecification = $this->_getAllBy(['fixed'=>'true'],$options);

        foreach ($listSpecification['data'] as $spec) {
            if ($spec->getChildren()->count() == 0 && !$spec->getParent()) {
                $list->add($spec);
                //$list->add([$spec->getid(),$spec->getIdx(),$spec->getName(),$spec->getChildren()->count()]) ;
            }

            if ($spec->getParent() && $spec->getParent()->getChildren()->count() == 1) {
                $list->add($spec->getParent()->getChildren()->first());
            }
            if ($spec->getParent() && $spec->getParent()->getChildren()->count() > 1) {
                if (!$list->contains($spec->getParent()->getChildren()->first())) {
                    $list->add($spec->getParent()->getChildren()->first());
                }

            }

        }
        return $list->all();
    }

    public function getSpecificationOnly($specificationId) {

        if ($this->accountService->getAccount()->getRoles() != "administrator") {
            $specification = $this->specificationRepository->findMyCompnay($specificationId);
        } else {
            $specification = $this->specificationRepository->findByMyCompnay(['id'=>$specificationId]);
        }

        if (!$specification) {
            throw new ApplicationException('Не найдена спецификация с ID:'.$specificationId,404);
        }

        if ($this->accountService->getAccount()->getCompany() !== $specification->getCompany()) {
            throw new ApplicationException('Не найдена спецификация с ID:'.$specificationId,404);
        }

        if ($this->accountService->getAccount()->getRoles() != "upravlenie") {
            return $specification;
        }

        if ($specification->getAutor() == $this->account) {
            return $specification;
        }

        if ($specification->getResponsibles()->contains($this->account)) {
            return $specification;
        }

        throw new ApplicationException('Не найдена спецификация с ID: ['.$specificationId.']',404);
    }

    public function actionHistory($idSpecification) {
        return $this->specificationRepository->history($idSpecification);
    }

    public function specificationChangeList($id) {
            $spec =  $this->_getById($id);
            $listSpec = new Collection();
            if ($spec->getChildren()->count()>0) {
               $listSpec->add($spec);
                foreach ($spec->getChildren() as $parentSpec) {
                    $listSpec->add($parentSpec);
                }
                return $listSpec->all();
            }
            if ($spec->getParent()) {

                $listSpec->add($spec->getParent());
                $spec = $spec->getParent();
                foreach ($spec->getChildren() as $parentSpec) {
                    $listSpec->add($parentSpec);
                }
                return $listSpec->all();
            }
    }

    public function newSpecification($arrKeyValue) {
        unset($arrKeyValue['parent']);
        $account = $this->accountService->getAccount();
        $role = $account->getRoles()->getService();

        if ($role != 'upravlenie' && $role != 'administrator') {
            throw new ApplicationException('Доступ запрещен ваша роль: '.$account->getRoles()->getName(),403);
        }

        if (!array_key_exists("objectId",$arrKeyValue)) {
            throw new ApplicationException('Ошибка objectId',400);
        }

        $object = $this->objectService->getObject($arrKeyValue['objectId']);


        if (!$object) {
            throw new ApplicationException('Ошибка! Объект с ID:'.$arrKeyValue['objectId'].' не найден',404);
        }

        if ( $object->getCompany() != $account->getCompany()){
            throw new ApplicationException('Ошибка! Объект с ID:'.$arrKeyValue['objectId'].' не найден',404);
        }

        if (array_key_exists("contract",$arrKeyValue) && array_key_exists('id', $arrKeyValue['contract'])) {
            $arrKeyValue['contract'] = $arrKeyValue['contract']['id'];
        }

        if (array_key_exists('files',$arrKeyValue) && is_array($arrKeyValue['files'])) {
            $filesListHash = $arrKeyValue['files'];
            unset($arrKeyValue['files']);
            foreach ($filesListHash as $fileHash) {
                $file = $this->fileService->getHashFile($fileHash);
                if ($file) {
                    $arrKeyValue['files'][]=$file;
                }

            }
        }

        $arrKeyValue['idx'] = 0;

        $arrKeyValue['object']=$object;

        //return $this->specificationRepository->loadNew($arrKeyValue);
        return $this->specificationRepository->create($arrKeyValue);

    }

    public function editSpecification($arrKeyValue) {
        unset($arrKeyValue['idx']);
        unset($arrKeyValue['material']);
        unset($arrKeyValue['parent']);
        unset($arrKeyValue['children']);
        $this->checkRoleUprav();
        if ($this->accountService->getThisRole()->getService() != 'administrator') {
            unset($arrKeyValue['parent']);
        }

        if ($this->accountService->getThisRole()->getService() == "upravlenie") {

        }
        if (array_key_exists('files',$arrKeyValue) && is_array($arrKeyValue['files'])) {
            $filesListHash = $arrKeyValue['files'];
            unset($arrKeyValue['files']);
            foreach ($filesListHash as $fileHash) {
                $file = $this->fileService->getHashFile($fileHash);
                if ($file) {
                    $arrKeyValue['files'][]=$file;
                }

            }
        }


        if (!array_key_exists("id",$arrKeyValue)) {
            throw new ApplicationException('Error specificationId',403);
        }

        $specificationId = $arrKeyValue["id"];
        $specification = $this->specificationRepository->find($specificationId);

        if (!$specification || $this->accountService->getAccount()->getCompany() != $specification->getObject()->getCompany()) {
            throw new ApplicationException('Не найдена спецификация с Id:'.$specificationId,404);
        }
        if ($specification->getFixed()) {
            throw new ApplicationException("Фиксированая запись",403);
        }
        $specComp = $specification->getObject()->getCompany();

        if ($this->accountService->getAccount()->getCompany() != $specComp) {
            throw new ApplicationException('Не найдена спецификация с Id:'.$specificationId,404);
        }

        if (array_key_exists("contract",$arrKeyValue) && is_array($arrKeyValue['contract']) && array_key_exists('id', $arrKeyValue['contract'])) {
            $arrKeyValue['contract'] = $arrKeyValue['contract']['id'];
        }


        return $this->_updateById($specification,$arrKeyValue);

    }

    public function listSpecificationMaterial($specificationId) {
        $specification = $this->getSpecificationOnly($specificationId);
        if (!$specification) {
            throw new ApplicationException('Не найдена спецификация с Id:'.$specificationId,404);
        }

        return $specification->getSpecificationMaterials();
    }

    public function fixSpec($id,$mode) {
        $this->checkRoleUprav();
        $spec = $this->_getById($id);
        if (!$spec ) {
            throw new ApplicationException('Не найдена спецификация с Id:'.$id,404);
        }
        $specComp = $spec->getObject()->getCompany();
        if ($this->accountService->getAccount()->getCompany() != $specComp) {
            throw new ApplicationException('Не найдена спецификация с Id:'.$id,404);
        }

        if ($spec->getRequisitions()->count()>0) {
            throw new ApplicationException('По спецификации с Id:['.$id.'] найдеы заявки. снятие фиксации невозможно',400);
        }

        return $this->_setFixed($id,$mode);
    }

    public function deleteSpecification($specificationId) {
        $this->checkRoleUprav();
        $specification = $this->getSpecificationOnly($specificationId);

        //$specification = $this->specificationRepository->findMyCompnay($specificationId);
        if (!$specification) {
            throw new ApplicationException("Спецификация с ID:".$specificationId." не найдена",404);
        }
        if ($specification->getFixed()) {
            throw new ApplicationException("Фиксированая запись",403);
        }
        if ($specification->getSpecificationMaterials()->count()>0) {
            throw new ApplicationException("В спецификации с ID [".$specificationId."] найдено [ ".$specification->getSpecificationMaterials()->count()." ] материал(а/ов) ",400);
        }

        if ($specification->getChildren()->count()>0) {
            throw new ApplicationException("В спецификации с ID [".$specificationId."] найдено [ ".$specification->getChildren()->count()." ] изменений.",400);
        }

        if ($specification->getRequisitions()->count()>0) {
            throw new ApplicationException("В спецификации с ID [".$specificationId."] найдено [ ".$specification->getRequisitions()->count()." ] заявок. ",400);
        }

        if ($specification->getSpecificationTypeworks()->count()>0) {
            throw new ApplicationException("В спецификации с ID [".$specificationId."] найдено [ ".$specification->getSpecificationTypeworks()->count()." ] виды работ. ",400);
        }

        if ($specification->getSpecificationResources()->count()>0) {
            throw new ApplicationException("В спецификации с ID [".$specificationId."] найдено [ ".$specification->getSpecificationResources()->count()." ] ресурсов. ",400);
        }

        if ($specification->getResponsibles()->count()>0) {
            throw new ApplicationException("В спецификации с ID [".$specificationId."] найдено [ ".$specification->getResponsibles()->count()." ] подпищиков. ",400);
        }


        if (count($this->invoicesRepository->invoicesSpecification($specification)) > 0 ) {
            throw new ApplicationException("В спецификации с ID [".$specificationId."] найдено [ ".count($this->invoicesRepository->invoicesSpecification($specification))." ] заявок. ",400);
        }

        $this->specificationRepository->delete($specification);

        return ['delete'=>$specificationId];
    }

    public function editSpecificationTypeWorks($specificationId,$specificationTypeWorkId,$arrKeyValue) {
        unset($arrKeyValue['specificationId']);
        unset($arrKeyValue['specificationTypeWorkId']);
        unset($arrKeyValue['fixed']);
        $specification = $this->getSpecificationOnly($specificationId);
        $this->repository = $this->specificationTypeWorksRepository;
        if ($this->_getById($specificationTypeWorkId)->getSpecification() == $specification)  {
            return $this->_updateById($specificationTypeWorkId,$arrKeyValue);
        }


    }

    public function checkSpecificationResponsible($specificationId,$accountId) {
        $specification = $this->getSpecificationOnly($specificationId);
        $account =  $this->accountService->getOne($accountId);
        return $this->specificationRepository->checkSpecificationResponsible($specification,$account);
    }

    public function newSpecificationTypeWorks($specificationId,$arrKeyValue) {
        $specification = $this->getSpecificationOnly($specificationId);
        if ($specification->getFixed()) {
            throw new ApplicationException("Фиксированая запись",403);
        }
        $arrKeyValue['specification'] = $specification;
        $newTypeWork = $this->specificationTypeWorksRepository->create($arrKeyValue);

        //return $newTypeWork;
        return $newTypeWork;
    }

    public function getSpecificationTypeWorks($specificationId) {
        return $this->_getById($specificationId)->getSpecificationTypeworks();
    }

    public function getSpecificationMaterial($specificationMaterialId) {

        $specificationMaterial = $this->specificationMaterialRepository->findMyCompnay($specificationMaterialId);
        //dd($specificationMaterial->getSpecification()->first()->getObject()->getCompany());
        if ($specificationMaterial && $specificationMaterial->getSpecification()->first()->getObject()->getCompany() == auth()->user()->getCompany()) {
            return $specificationMaterial;
        }


    }




    public function editSpecificationMaterial($specificationId,$specificationMaterialId,$arrKeyValue) {
        $this->checkRoleUprav();
        unset($arrKeyValue['specificationId']);
        unset($arrKeyValue['specificationMaterialId']);
        $specification = $this->getSpecificationOnly($specificationId);

        if (!$specification || $this->accountService->getAccount()->getCompany() != $specification->getObject()->getCompany()) {
            throw new ApplicationException("Не найдена спецификация с Id:$specificationId",404);
        }
        if ($specification->getFixed()) {
            throw new ApplicationException("Фиксированая запись",403);
        }
        $specificationMaterial = $this->specificationMaterialRepository->findMyCompnay($specificationMaterialId);

        if (!$specification->getSpecificationMaterials()->contains($specificationMaterial)) {
            throw new ApplicationException("Not found Specification Material  Spec ID:$specificationMaterialId",404);
        }


        if (!$specificationMaterial) {
            throw new ApplicationException("Not found Specification Material ID:$specificationMaterialId",404);
        }

        if (array_key_exists('material',$arrKeyValue)){
            $material = $this->materialService->_getById($arrKeyValue['material']);
            unset($arrKeyValue['material']);

            if ($material) {
                $arrKeyValue['material'] =  $material;
                $arrKeyValue['unit_code'] = $material->getUnit()->getCode();
            }

        } else {
            unset($arrKeyValue['material']);
        }

        if (array_key_exists('unit',$arrKeyValue) && is_array($arrKeyValue['unit']) && array_key_exists('code',$arrKeyValue['unit'])){
            $arrKeyValue['unit_code'] = $arrKeyValue['unit']['code'];
        }

        if (array_key_exists('unit_code',$arrKeyValue)){
            $unit_code = $arrKeyValue['unit_code'];

            unset($arrKeyValue['unit_code']);
            $unit = $this->materialService->getUnitCode($unit_code);
            if (!$unit) {
                $unit = $this->materialService->getUnitCodeById($unit_code);
                if (!$unit) {
                    throw new ApplicationException("Не найдена еденица измерения с кодом:$unit_code",404);
                }
            } else {
                $arrKeyValue['unit'] = $unit;
            }

        }

        if (array_key_exists('is_group',$arrKeyValue) && $arrKeyValue['is_group'] === true){
            $arrKeyValue['unit'] = null;
        }

        //dd($arrKeyValue);
        return $this->specificationMaterialRepository->update($specificationMaterial,$arrKeyValue);
    }

    public function newSpecificationMaterial($specificationId,$arrKeyValue) {
        $this->checkRoleUprav();
        unset($arrKeyValue['specificationId']);

        $specification = $this->getSpecificationOnly($specificationId);



        if ($this->accountService->getAccount()->getCompany() != $specification->getObject()->getCompany()) {
            throw new ApplicationException('Не найдена спецификация с Id:'.$id,404);
        }

        if ($specification->getFixed()) {
            throw new ApplicationException("Фиксированая запись",403);
        }
        $arrKeyValue['specification'] = $specification;
        if (!array_key_exists('isGroup',$arrKeyValue)){
            $arrKeyValue['isGroup'] = false;
        }

        if (array_key_exists('isGroup',$arrKeyValue) && $arrKeyValue['isGroup'] === true){
            $arrKeyValue['isGroup'] = true;
        } else {
            $arrKeyValue['isGroup'] = false;
        }

        if (array_key_exists('material',$arrKeyValue)){
            $material = $this->materialService->_getById($arrKeyValue['material']);
            unset($arrKeyValue['material']);
            if ($material) {
                $arrKeyValue['material']=$material;

                $arrKeyValue['unit'] = $material->getUnit()->getCode();
            }

        }

        if (array_key_exists('unit_code',$arrKeyValue)){
            $unit_code = $arrKeyValue['unit_code'];
            $unit = $this->materialService->getUnitCode($unit_code);
            if (!$unit) {
                throw new ApplicationException("Не найдена еденица измерения с кодом:$unit_code",404);
            }
            $arrKeyValue['unit'] = $unit;
        }


        return $this->specificationMaterialRepository->createMaterial($specification,$arrKeyValue);

    }

    public function historySpecificationMaterial($specificationMaterialId) {
        if ($this->getSpecificationMaterial($specificationMaterialId)) {
            return $this->specificationMaterialRepository->history($specificationMaterialId);
        }
    }


    public function deleteSpecificationMaterial($specificationId,$specificationMaterialId) {
        $this->checkRoleUprav();


        $specification = $this->getSpecificationOnly($specificationId);
        if ($specification->getFixed()) {
            throw new ApplicationException("Фиксированая запись",403);
        }

        $material = $this->getSpecificationMaterial($specificationMaterialId);

        if ($specification->getSpecificationMaterials()->contains($material)) {
            $this->sendSystemNotification('Удаление материала спецификации', "Материал ".$material->getFullname()." удален.");
           return  $this->specificationMaterialRepository->deleteByUuid($material);
        }

        abort('400','Ошибка удаления метериала спецификации');
    }

    public function readySpecificationNoFix($specificationId) {
        $specification = $this->getSpecificationOnly($specificationId);
        return $this->specificationRepository->getSpecificationNoFixed($specification);
    }


    public function readySpecificationFix($specificationId) {
        $specification = $this->getSpecificationOnly($specificationId);

        return $this->specificationRepository->getSpecificationFixed($specification);
    }

    public function SpecificationChangeAdd($arrKeyValue) {
        $this->checkRoleUprav();
        $spec = $this->getSpecificationOnly($arrKeyValue['id']);
        if ($this->accountService->getAccount()->getCompany() != $spec->getObject()->getCompany()) {
            throw new ApplicationException('Не найдена спецификация с Id:'.$id,404);
        }

        if (! $spec ) {
            throw new ApplicationException("Не найдена спецификация с Id:".$arrKeyValue['id'],400);
        }

        return  $this->specificationRepository->changeAdd($arrKeyValue['id'],$arrKeyValue);
    }

    public function specificationMaterialSetСalculation($specificationId,array $materials) {

        $specification = $this->getSpecificationOnly($specificationId);
        return $this->materialCalculationRepository->setСalculation($specification,$materials);

    }


    public function specificationMaterialSearch($specificationId,$text) {
        $specification = $this->getSpecificationOnly($specificationId);

        return $this->specificationMaterialRepository->searchMaterial($specification,$text);
    }

    public function specificationMaterialRemnants($specificationId,$idMaterialSpecification='') {
        $specification = $this->getSpecificationOnly($specificationId);
        if ($idMaterialSpecification) {
            $materialSpecification = $this->getSpecificationMaterial($idMaterialSpecification);
        } else {
            $materialSpecification=null;
        }


        return $this->specificationRepository->specificationMaterialRemnants($specification,$materialSpecification);
    }

    public function specificationResponsible($specificationId) {
        $specification = $this->getSpecificationOnly($specificationId);
        return $specification->getResponsibles();
    }

    public function specificationResponsibleAdd($specificationId, $accountId) {
        $this->checkRoleUprav();

        $specification = $this->getSpecificationOnly($specificationId);
        $account = $this->accountService->getAccountMyCompnay($accountId);

        return $this->specificationRepository->addResponsibleSpecification($specification,$account);
    }

    public function specificationResponsibleDelete($specificationId, $accountId) {
        $this->checkRoleUprav();
        $specification = $this->getSpecificationOnly($specificationId);
        $account = $this->accountService->getAccountMyCompnay($accountId);
        return $this->specificationRepository->deleteResponsibleSpecification($specification,$account);
    }

    public function setSpecificationContract($specificationId,$contractId) {
        $specification = $this->getSpecificationOnly($specificationId);
        return $this->specificationRepository->setSpecificationContract($specification,$contractId);
    }
    public function getSpecificationContract($specificationId,$options=[]) {
        $specification = $this->getSpecificationOnly($specificationId);


        return $this->specificationRepository->getSpecificationContract($specification,$options);
    }
    public function removeSpecificationContract($specificationId) {
        $specification = $this->getSpecificationOnly($specificationId);
        return $this->specificationRepository->removeSpecificationContract($specification);

    }

    public function importSpecFile(UploadedFile $file)
    {
        if (!$file->isValid() || !in_array($file->getClientMimeType(), ['application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.ms-excel'])) {
            abort(500,"Недопустимый файл!");
        }

        try {
            $spreadsheet = IOFactory::load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Ошибка при загрузке файла: ' . $e->getMessage());
            throw new \Exception('Ошибка при обработке файла');
        }


        $rows = $sheet->toArray();
        foreach ($rows as $rowIndex => $row) {
            // Пример маппинга строки на сущности Doctrine
            if ($rowIndex === 0) {
                // Пропускаем заголовок
                continue;
            }
            $specification = new Specification();
            $specification->name = $row[0] ?? null;
            $specification->value = $row[1] ?? null;

            \Illuminate\Support\Facades\Log::info($row);
            //$this->notificationService->sendNotificationSystem("Импорт спеки",$file->getClientMimeType());
        }
        $this->notificationService->sendNotificationSystem("Импорт спеки",$file->getClientMimeType());
        return $rows;
    }
}
