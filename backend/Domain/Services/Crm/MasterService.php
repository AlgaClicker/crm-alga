<?php

namespace Domain\Services\Crm;
use Core\Exceptions\ApplicationException;
use Domain\Contracts\Repository\Document\MaterialRequisitionRepositoryContracts;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Crm\MasterServiceContract;
use Domain\Contracts\Services\Crm\RequisitionServiceContract;
use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Domain\Contracts\Services\Crm\WorkpeopleServiceContract;
use Domain\Contracts\Services\Directory\MaterialServiceContract;
use Domain\Contracts\Services\NotificationServiceContracts;
use Domain\Contracts\Services\ChatServiceContracts;
use Domain\Entities\Business\Master\Requisition;
use Domain\Entities\Business\Objects\Specification;
use Domain\Services\AbstractService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class MasterService extends AbstractService implements MasterServiceContract
{
    protected WorkpeopleServiceContract $workpeopleService;
    private SpecificationsServiceContracts $specificationsService;
    private AccountServiceContracts $accountService;
    private MaterialRequisitionRepositoryContracts $materialRequisitionRepository;
    private MaterialRequisitionRepositoryContracts $materialRepository;
    private NotificationServiceContracts $notificationService;
    private ChatServiceContracts $chatService;
    private MaterialServiceContract $materialService;
    private RequisitionServiceContract $requisitionService;


    public function __construct(
        SpecificationsServiceContracts $specificationsService,
        AccountServiceContracts $accountService,
        WorkpeopleServiceContract $workpeopleService,
        NotificationServiceContracts $notificationService,
        MaterialRequisitionRepositoryContracts $materialRequisitionRepository,
        ChatServiceContracts $chatService,
        MaterialServiceContract $materialService,
        RequisitionServiceContract $requisitionService
    ){
        $this->specificationsService = $specificationsService;
        $this->notificationService = $notificationService;
        $this->accountService = $accountService;
        $this->workpeopleService = $workpeopleService;
        $this->materialRequisitionRepository = $materialRequisitionRepository;

        $this->repository = $materialRequisitionRepository;
        $this->chatService = $chatService;
        $this->materialService = $materialService;
        $this->requisitionService = $requisitionService;
    }

    public function getListMySpecifications($options=[]) {
            return $this->specificationsService->getSpecificationListMaster($this->accountService->getAccount());
    }

    public function getListMaterialsSpecification($specificationId,$options=[]) {
        $spec = $this->specificationsService->getSpecificationOnly($specificationId);

        if (!$spec) {
            throw new ApplicationException("Спецификация с ID [$specificationId] не найдена!",404);
        }
        return $this->specificationsService->listSpecificationMaterial($specificationId);

        if ($spec->getResponsibles()->contains($this->accountService->getAccount()) || auth()->user()->getRoles()->getService() == 'upravlenie') {
            return $this->specificationsService->listSpecificationMaterial($specificationId);
        }
    }


    public function newRequisitionMaterials($specificationId,\DateTimeImmutable $dateEnd,$arrayKeyVal,$description='') {
        $spec = $this->specificationsService->getSpecificationOnly($specificationId);
        $requisition = $this->materialRequisitionRepository->newRequisitionMaterials($spec,$dateEnd,$arrayKeyVal,$description);
        return $requisition;

    }

    public function requisitionEdit($requisitionId, $arrKeyVal) {
        $requisition = $this->_getById($requisitionId);
        $this->repository = $this->materialRequisitionRepository;
        //return  $this->_updateById($requisitionId,$arrKeyVal);
        return $this->materialRequisitionRepository->update($requisition,$arrKeyVal);
    }

    public function requisitionFixed($requisitionId) {
        if (!$this->_getById($requisitionId)) {
            throw new ApplicationException("Заявка с ID [$requisitionId] не найдена!",404);
        }


        if ( $this->_getById($requisitionId)->getAutor() == auth()->user() || auth()->user()->getRoles()->getService() == 'upravlenie') {
            if ($this->_getById($requisitionId)->getFixed()) {
                throw new ApplicationException("Заявка с ID [$requisitionId] уже фиксирована!",400);
            }

            $requisition =  $this->_getById($requisitionId);
            $requisition = $this->materialRequisitionRepository->requisitionSetFixed($requisition);
            $this->notificationService->requisitionFixedSend($requisition);


            return $requisition;
        }
    }

    public function requisitionUnFixed($requisitionId) {
        if (!$this->_getById($requisitionId)) {
            throw new ApplicationException("Заявка с ID [$requisitionId] не найдена!",404);
        }

        if ($this->_getById($requisitionId)->getManager()) {
            throw new ApplicationException("Заявке с ID [$requisitionId] уже назначен менеджер, для разблокировки обратитесь к менеджеру заявки.ю!",400);
        }
        if ( $this->_getById($requisitionId)->getAutor() == auth()->user() || auth()->user()->getRoles()->getService() == 'upravlenie') {
            if ($this->_getById($requisitionId)->getManager()) {
                throw new ApplicationException("Заявка с ID [$requisitionId] уже взята в работу менеджером, коректировка запрещена!",400);
            }
            return $this->materialRequisitionRepository->requisitionSetUnFixed($this->_getById($requisitionId));
        }
    }

    public function requisitionDelete($requisitionId) {
        $requisition = $this->materialRequisitionRepository->requisitionDelete($this->_getById($requisitionId));
        $this->notificationService->sendNotificationSystem('Заявка №'.$requisition->getNumber().' удалена','Успешно удалена пользовательем: '.auth()->user()->getUsername());
        return true;
    }

    public function getOneRequisition($id) {
        $this->repository = $this->materialRequisitionRepository;
        $id_requisition = $this->_getById($id);
        return $this->materialRequisitionRepository->getRequisitionOnly($id_requisition);
    }

    public function getAllByRequisition($options = []) {
        $this->repository = $this->materialRequisitionRepository;


        if ($this->accountService->getAccount()->getRoles()->getService() == "upravlenie") {
            return $this->_getAllBy([],$options);
        }

        return $this->materialRequisitionRepository->requisitionMasterList($options);
    }

    public function getRequisitionOtherId($requisitionOtherId) {

    }

    public function addRequisitionOther($arrayKeyValue) {
        $arrayKeyAttribute=[];
        if (array_key_exists('specificationId',$arrayKeyValue)) {
            $spec = $this->specificationsService->getSpecificationOnly($arrayKeyValue['specificationId']);
            unset($arrayKeyValue['specificationId']);
            if ($spec) {
                $arrayKeyAttribute['specificationId'] = $spec;
            }
         }
        if (array_key_exists('description',$arrayKeyValue)) {
            $arrayKeyAttribute['description'] = $arrayKeyValue['description'];
        }
        $arrayKeyAttribute['type']= 'other';
        $arrayKeyAttribute['master'] = auth()->user();
        $arrayKeyAttribute['end_at'] = (new \DateTimeImmutable($arrayKeyValue['end_at']))->modify('tomorrow');
        $materials_data = $arrayKeyValue['materials'];
        unset($arrayKeyValue['materials']);

        foreach ($materials_data as $material_data) {
            $mat=[];
            if (array_key_exists('material',$material_data)) {
                $material_dir = $this->materialService->getById($material_data['material']['id']);

                if ($material_dir) {
                    $mat['material'] = $material_dir;
                    $mat['quantity'] = $material_data['quantity'];
                } else {
                    unset($mat['material']);
                }

            } else {
                $mat['name'] =  $material_data['name'];
                $mat['unit'] =  $this->materialService->getUnitCode($material_data['unit']['code']);
                $mat['quantity'] = $material_data['quantity'];
            }
            if (array_key_exists('description',$material_data)) {
                $mat['description'] = $material_data['description'];
            }
            if ($mat) {
                $arrayKeyAttribute['materials'][] = $mat;
            }
        }

        $requisition = $this->materialRequisitionRepository->createRequisitionOther($arrayKeyAttribute);
        if (array_key_exists('fixed',$arrayKeyValue) && is_bool($arrayKeyValue['fixed'])) {
            if ($arrayKeyValue['fixed'] === true) {
                $requisition =  $this->requisitionFixed($requisition->getId());
            } else {

            }
        }
        return $requisition;

    }

}
