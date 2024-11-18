<?php
namespace Domain\Services\Crm;

use Core\Exceptions\ApplicationException;
use Domain\Contracts\Repository\Document\InvoicesRequisitionRepositoryContract;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\ChatServiceContracts;
use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Domain\Contracts\Services\Directory\MaterialServiceContract;
use Domain\Contracts\Services\NotificationServiceContracts;
use Domain\Entities\Business\Document\Requisition\Invoice;
use Domain\Entities\Business\Master\Requisition;
use Domain\Services\AbstractService;
use Domain\Contracts\Repository\Document\MaterialRequisitionRepositoryContracts;
use Domain\Contracts\Services\Crm\RequisitionServiceContract;
use Illuminate\Support\Facades\Log;
use mysql_xdevapi\Exception;
use Ramsey\Collection\Collection;
use Domain\Contracts\Services\FileServiceContracts;
use Core\Mail\DeliveryRequisitionEmail;
use Core\Mail\NotificationEmail;
use Illuminate\Support\Facades\Mail;
class RequisitionService extends AbstractService implements RequisitionServiceContract
{

    protected MaterialRequisitionRepositoryContracts $materialRequisitionRepository;
    protected ChatServiceContracts $chatService;
    protected NotificationServiceContracts $notificationService;
    protected MaterialServiceContract $materialService;
    protected InvoicesRequisitionRepositoryContract $invoicesRequisitionRepository;
    protected SpecificationsServiceContracts $specificationsService;
    protected AccountServiceContracts $accountService;
    protected FileServiceContracts $fileService;

    public function __construct(
        MaterialRequisitionRepositoryContracts $materialRequisitionRepository,
        ChatServiceContracts $chatService,
        NotificationServiceContracts $notificationService,
        MaterialServiceContract $materialService,
        InvoicesRequisitionRepositoryContract $invoicesRequisitionRepository,
        SpecificationsServiceContracts $specificationsService,
        AccountServiceContracts $accountService,
        FileServiceContracts $fileService

    ){
        $this->materialRequisitionRepository = $materialRequisitionRepository;
        $this->chatService = $chatService;
        $this->notificationService = $notificationService;
        $this->materialService = $materialService;
        $this->invoicesRequisitionRepository = $invoicesRequisitionRepository;
        $this->specificationsService =  $specificationsService;
        $this->accountService = $accountService;
        $this->fileService = $fileService;
        parent::__construct($materialRequisitionRepository);
   }


    public function getRequisitionListNoManager($options=[]) {
        return $this->materialRequisitionRepository->getListRequisitionNoManager($options);
    }
   public function getRequisitionList($options=[]) {
       //$options = $this->_setOptionsResponse($options);
       return $this->materialRequisitionRepository->getListRequisition($options);
   }

   public function listRequisitionOther($options)
   {

       return $this->_getAllBy(['autor'=>auth()->user()],$options);
   }

    public function listRequisition($options=[])
    {

        return  $this->_getAllBy([],$options);
    }

    public function getRequisitionListMy($options=[]) {

       $options = $this->_setOptionsResponse($options,1);

        if (auth()->user()->getRoles()->getService() == "upravlenie") {

            return $this->_getAllBy([],$options);
        }

        if (array_key_exists('filter',$options)) {
            $options['filter'] = array_merge($options['filter'],['manager'=>$this->account->getId()->serialize()]);
        } else {
            $options['filter'] = ['manager'=>$this->account->getId()->serialize()];
        }

       return $this->materialRequisitionRepository->getListRequisitionMy($options);
   }

   public function  listMaterialRequisition($requisitionId,$options=[]){
       $requisition = $this->_getById($requisitionId);
       if (!$requisition) {
           abort(404,"Заявка с ID [$requisitionId] не найдена!");
       }

       return $this->materialRequisitionRepository->listMaterialRequisition($requisition,$options);
   }

   public function requisitionSetManager($requisitionId) {
       $requisition = $this->_getById($requisitionId);
       if (!$requisition) {
           throw new ApplicationException("Заявка с ID [$requisitionId] не найдена!",404);
       }

       if ($requisition->getStatus() == 'canceled') {
           abort(403,"Заявка была отменена. Дальнейшие действия невозможны.");
       }

       $requisition = $this->materialRequisitionRepository->requisitionSetManager($requisition, auth()->user());
       $specification = $requisition->getSpecification();
       if ($specification) {
           $body_message_push = "Зявка по спецификации ".$specification->getName()."<br>\r\n";
           $body_message_push .= "Объект ".$specification->getObject()->getName()."<br>\r\n";

       } else {
           $body_message_push = "Зявка от: ".$requisition->getAutor()->getUsername()."<br>\r\n";
       }

       $body_message_push .= "Поставить к ".$requisition->getEndAt()->format('d-m-Y')."\r\n";
       $this->chatService->newChatRequisition($requisition,$body_message_push);
       $this->notificationService->requisitionSendTo($requisition,$requisition->getAutor(),"Назначен Менеджер");
       $this->notificationService->sendNotificationSystemAccount($requisition->getAutor(),'В работе у менеджера',$body_message_push);
       //$this->sendSystemNotification();

       return $requisition;
   }

   public  function editRequisitionOther($requisitionId,$end_at,$description='') {

       $requisition = $this->_getById($requisitionId);
       if (!$requisition) {
           abort(404,"Не найдена заявка с ID [$requisitionId]");
       }
       if ($requisition->getStatus() == 'canceled') {
           abort(403,"Заявка была отменена. Дальнейшие действия невозможны.");
       }


       $arrayKeyAttribute['endAt'] = (new \DateTimeImmutable($end_at))->modify('tomorrow');
       if ($description) {
           $arrayKeyAttribute['description']  = $description;
       }

       return $this->_updateById($requisitionId,$arrayKeyAttribute);
   }

   public function getRequisition($requisitionId): Requisition {
       $requisition = $this->_getById($requisitionId);
       if (!$requisition) {
           throw new ApplicationException("Заявка с ID [$requisitionId] не найдена!",404);
       }

       $requisition =  $this->materialRequisitionRepository->getRequisitionOnly($requisition);

       if (auth()->user()->getRoles()->getService() === "upravlenie") {
           return $requisition;
       }

       if ($requisition->getAutor() === auth()->user()) {
           return $requisition;
       }

       if ($requisition->getManager() === auth()->user() ) {
           return $requisition;
       }

       if ( $requisition->getStatus() == 'canceled' && auth()->user()->getRoles()->getService() !== "upravlenie") {
           abort(403,"Заявка отменена, доступ закрыт");
       }


       if ($requisition->getAutor() === auth()->user() || $requisition->getManager() === auth()->user()) {
           if ($requisition->getAutor() === auth()->user() && $requisition->getManager()) {

           }
           return $requisition;
       }

       if ($requisition->getManager() === null && $requisition->getSpecification() == null) {
            return $requisition;
       }



       if ($requisition->getSpecification()) {
           $specification = $this->specificationsService->getSpecificationOnly($requisition->getSpecification());
       } else {
           $specification = null;
       }





        if ($specification && $requisition->getSpecification() && !$this->specificationsService->checkSpecificationResponsible($specification,auth()->user())) {
            abort('403','Вы не являетесь ответственным по данной спецификации, которая указана в заявке');
        } elseif ($requisition->getSpecification() && $this->specificationsService->getSpecificationOnly($requisition->getSpecification())->getResponsibles()->contains(auth()->user())) {
            return $requisition;
        }



       throw new ApplicationException("Заявка с ID [$requisitionId] не найдена!",404);

   }

   public function getListMessagesChatRequisition($requisitionId,$options=[]) {
       $requisition = $this->getRequisition($requisitionId);
       return $this->chatService->getListChatRequisition($requisition,$options);
   }

   public function sendMessagesChatRequisition($requisitionId,$message,$files=[]) {
       $requisition = $this->getRequisition($requisitionId);

       if ($requisition->getStatus() == 'canceled') {
           abort(403,"Заявка была отменена. Дальнейшие действия невозможны.");
       }

       if (!$requisition) {
           throw new ApplicationException("Заявка с ID [$requisitionId] не найдена!",404);
       }

       $listFiles = [];
       if ($files) {
           foreach ($files as $hashFile) {
               $file = $this->fileService->getOne($hashFile);
               if ($file) {
                   $listFiles[] = $file;
               }
           }
       }
       $message = $this->chatService->sendMessageChatRequisition($requisition,$message,$listFiles);
       //$this->notificationService->sendMail
       return $message;
   }

   private function _setAttributeRequisitionMaterial($arrayKeyValue) {
       if (array_key_exists('unit',$arrayKeyValue)) {
           $arrayKeyValue['unit']=$this->materialService->getUnitCode($arrayKeyValue['unit']);
       }

       if (array_key_exists('material',$arrayKeyValue)) {
           $material = $this->materialService->getById($arrayKeyValue['material']['id']);
           unset($arrayKeyValue['material']);
           if ($material) {
               $arrayKeyValue['material']=$material;
               $arrayKeyValue['name'] = $material->getName();
               $arrayKeyValue['unit']=$material->getUnit();
           }
       }
        return $arrayKeyValue;
   }

   public function newMaterialSpecificationRequisition($requisitionId,$arrayKeyValue){
       $requisition = $this->getRequisition($requisitionId);
       if ($requisition->getStatus() == 'canceled') {
           abort(403,"Заявка была отменена. Дальнейшие действия невозможны.");
       }

       $specificationMaterial = $this->specificationsService->getSpecificationMaterial($arrayKeyValue['material']['id']);

       if ($specificationMaterial->getIsGroup()) {
           abort('400','Указанный материал является группой');
       }
       if (!$requisition->getSpecification()) {
           abort('400','Заявка не по спецификации');
       }


       if (!$specificationMaterial->getSpecification()->contains($requisition->getSpecification())) {
           abort('400','КУказанный материал не принадлежтит спецификации по которой созданая заявка');
       }

        return $this->materialRequisitionRepository->newRequisitionMaterialSpecification($requisition,$specificationMaterial,$arrayKeyValue);

   }

   public function newMaterialRequisitionOther($requisitionId,$arrayKeyValue){

       $requisition = $this->getRequisition($requisitionId);
       if ($requisition->getFixed() && $requisition->getStatus() != "new" && $requisition->getManager() !== auth()->user()) {
           abort('400','Фиксированя заявка');
       }

       $arrayKeyValue=$this->_setAttributeRequisitionMaterial($arrayKeyValue);

       return $this->materialRequisitionRepository->createMaterialRequisitionOther($requisition,$arrayKeyValue);
   }

   public function deleteMaterialRequisitionOther($requisitionId,$materialRequisitionId) {
       $requisition = $this->getRequisition($requisitionId);
       if ($requisition->getFixed() && $requisition->getStatus() != "new" && $requisition->getManager() !== auth()->user()) {
           abort('403','Фиксированя заявка, удаление запрещено!');
       }

       return $this->materialRequisitionRepository->deleteMaterialRequisitionOther($requisition,$materialRequisitionId);
   }

   public function editMaterialRequisitionOther($requisitionId,$materialRequisitionId,$arrayKeyValue) {
       $requisition = $this->getRequisition($requisitionId);
       if ($requisition->getFixed() && $requisition->getStatus() != "new" && $requisition->getManager() !== auth()->user()) {
           abort('403','Фиксированя заявка, правка запрещена!');
       }
       if ($requisition->getStatus() == 'canceled') {
           abort(403,"Заявка была отменена. Дальнейшие действия невозможны.");
       }

       $arrayKeyValue=$this->_setAttributeRequisitionMaterial($arrayKeyValue);

       return $this->materialRequisitionRepository->editMaterialRequisitionOther($requisition,$materialRequisitionId,$arrayKeyValue);
   }


   public function snabzheniyeRequisitionWork($requisitionId,$arrayKeyValue) {

   }

   public function requisitionCalculation($requisitionId, \DateTimeImmutable $date) {
       $requisition = $this->getRequisition($requisitionId);
       return $this->invoicesRequisitionRepository->requisitionCalculation($requisition,$date);
   }

   public function deleteRequisitionInvoice($requisitionId,$invoiceId) {
       $requisition = $this->getRequisition($requisitionId);
       return $this->invoicesRequisitionRepository->deleteRequisitionInvoice($requisition,$invoiceId);
   }

   public function snabzheniyeRequisitionWorkAdd($requisitionId,$arrayKeyValue) {
       $requisition = $this->getRequisition($requisitionId);
       if ($requisition->getStatus() == 'canceled') {
           abort(403,"Заявка была отменена. Дальнейшие действия невозможны.");
       }

       $this->unsetAttributtes($arrayKeyValue);

       $arrayKeyValue['status']="new";
       $invoiceRequisition = $this->invoicesRequisitionRepository->newInvoiceRequisition($requisition, $arrayKeyValue);
       $this->notificationService->sendNotificationSystemAccount(auth()->user(),'Новая Счет-Заявка',"Счет-заявка № ".$invoiceRequisition->getNumber()." на сумму ".$invoiceRequisition->getAmount()." руб. Создана.");
       if ($invoiceRequisition->getStatus() == 'completed') {
           $message_compiled = "message";
           $this->notificationService->sendRoleNotification('upravlenie','Заявка №'.$requisition->getNumber()." закрыта!",$message_compiled);
       }
       $creted_at = \Carbon\Carbon::parse($invoiceRequisition->getRequisition()->getCreatedAt())->format('m-d-Y') ;
       $title =  "Поставка по заявке  №: ".$invoiceRequisition->getRequisition()->getNumber().' от '.$creted_at;
       try {

           Mail::to($requisition->getAutor()->getEmail())->send(new DeliveryRequisitionEmail($invoiceRequisition,$title));
       } catch (Exception $e) {

       }

       return  $invoiceRequisition;
   }

   public function invoicesRequisitionList($requisitionId,$options=[]){
       $requisition = $this->getRequisition($requisitionId);

       return  $this->invoicesRequisitionRepository->invoicesRequisitionList($requisition,$options);
   }

   public function invoicesListManager($options=[])
   {
       return  $this->invoicesRequisitionRepository->invoicesListManager($options);
   }
    public function unSetMaterialRequisitionDirectory($requisitionId,$materialRequisitionId) {
        $requisition = $this->getRequisition($requisitionId);

        if ($requisition->getManager() && $requisition->getManager() === auth()->user() || auth()->user()->getRoles()->getService() === "upravlenie") {
            $material_requisition = $this->materialRequisitionRepository->getMaterialRequisition($requisition, $materialRequisitionId);
            if (!$material_requisition) {
                abort(404, "Не  найдена запись материала заявки!");
            }
            $material_requisition = $this->materialRequisitionRepository->unSetMaterialRequisitionDirectory(
                $requisition,
                $material_requisition
            );

            try {
                $this->notificationService->sendNotificationSystem("Материал заявки отвязали от справочника","Материал [".$material_requisition->getId()."] привязан к заявке №".$requisition->getNumber());
            } catch (\Exception $e ) {

            }

            return $material_requisition;
        }
    }

   public function setMaterialRequisitionDirectory($requisitionId,$materialRequisitionId,$material_directory) {
       $requisition = $this->getRequisition($requisitionId);

       if ($requisition->getStatus() == 'canceled') {
           abort(403,"Заявка была отменена. Дальнейшие действия невозможны.");
       }

       if ($requisition->getManager() && $requisition->getManager() === auth()->user() || auth()->user()->getRoles()->getService() === "upravlenie") {
            $material_requisition = $this->materialRequisitionRepository->getMaterialRequisition($requisition,$materialRequisitionId);
            if (!$material_requisition) {
                abort(404,"Не найдена запись материала заявки!");
            }

           $material_directory = $this->materialService->_getById($material_directory);
           if (!$material_directory) {
               abort(404,"Не найден материал в справочнике!");
           }
            $material_requisition = $this->materialRequisitionRepository->setMaterialRequisitionDirectory(
                $requisition,
                $material_requisition,
                $material_directory
            );
            $this->notificationService->sendNotificationSystem("Привязка материала","Материал [".$material_requisition->getDirectoryMaterial()->getName()."] привязан к заявке №".$requisition->getNumber());
           return $material_requisition;
       }

       abort(403,"Нет прав на установку материала!");
   }


   public function showMaterialRequisition($requisitionId,$materialRequisitionId) {
        $requisition = $this->getRequisition($requisitionId);

        if ($requisition->getManager() && $requisition->getManager() === auth()->user() || auth()->user()->getRoles()->getService() === "upravlenie") {
            $material_requisition = $this->materialRequisitionRepository->getMaterialRequisition($requisition, $materialRequisitionId);
            if (!$material_requisition) {
                abort(404, "Не найдена запись материала заявки!");
            }
            return $this->materialRequisitionRepository->getMaterialRequisitionOne(
                $requisition,
                $material_requisition,
            );
        }
        abort(403,"Нет прав доступа!");
   }


   public function cancelRequisition($requisitionId,$description) {
        $requisition = $this->getRequisition($requisitionId);

        if ($requisition->getInvoices()->count()>0) {
            //abort('400',"У заявки имеются Счет-Заявки отмена невозможна");
        }
        $requisition = $this->materialRequisitionRepository->cancelRequisition($requisition,$description);
        //$requisition = $this->_updateById($requisition,['status'=>'canceled']);

        $notification = $this->notificationService->sendNotificationSystem('Заявка № '.$requisition->getNumber().' отменена!','Пиричина: '.$requisition->getDescription());
        if ($requisition->getAutor()) {
            $this->notificationService->sendNotificationSystemAccount($requisition->getAutor(),'Заявка №'.$requisition->getNumber().' отменена!','Пиричина: '.$requisition->getDescription());
        }
        Mail::to($requisition->getAutor()->getEmail())->queue(new NotificationEmail($notification));
        if ($requisition->getManager()) {
            Mail::to($requisition->getManager()->getEmail())->queue(new NotificationEmail($notification));
        }
        return $requisition;
   }


   public function specificationInvoicesRequisitionList($specificationId,$options=[]) {
        return $this->invoicesRequisitionRepository->specificationInvoicesRequisitionList($specificationId,$options);
   }


   public function cleanManager($requisitionId) {
        $requisition = $this->getRequisition($requisitionId);
        if ($requisition->getInvoices()->count()>0) {
            abort('400',"У заявки имеются Счет-Заявки отмена менеджера невозможна");
        }

       if ($this->accountService->getThisRole()->getService() !== 'upravlenie') {
           abort('400',"Указанный аккаунт [".auth()->user()->getUsername()."] не является ролью Управление!");
       }

        return $this->materialRequisitionRepository->cleanManager($requisition);
   }


   public function setManager($requisitionId, $accountId=null) {
        if (!$accountId) {
            $accountId = auth()->user()->getId();
        }

       $requisition = $this->getRequisition($requisitionId);
       if ($requisition->getStatus() == 'canceled') {
           abort(403,"Заявка была отменена. Дальнейшие действия невозможны.");
       }

       $account = $this->accountService->getAccountMyCompnay($accountId);



       if ($account->getRoles()->getService() !== 'snabzenie') {
            abort('400',"Указанный аккаунт [".$account->getUsername()."] не является ролью Снабжение!");
        }

       if ($this->accountService->getThisRole()->getService() === "upravlenie") {
           $requisition = $this->materialRequisitionRepository->requisitionSetManager($requisition, $account);

           $this->notificationService->requisitionSendAutor($requisition,"Назначен менеджер");
           return $requisition;
       }

       if ($requisition->getSpecification() && !$requisition->getSpecification()->getResponsibles()->contains($account)) {
           abort('400',"Указанный аккаунт [".$account->getUsername()."] не является ответственным по спецификации [".$requisition->getSpecification()->getName()."] !");
       }

       if  ($requisition->getManager() ) {
            abort('400',"У заявки имеется назначенный менеджер!");
        }

       $requisition = $this->materialRequisitionRepository->requisitionSetManager($requisition, $account);
       $title = "Назначен менеджер";
       $this->notificationService->requisitionSendAutor($requisition,$title);

       return $requisition;
   }

   public function getInvoice($id_invoice): Invoice|null
   {
       return $this->invoicesRequisitionRepository->getInvoice($id_invoice);
   }

   public function deliveryMaterials($requisitionId,$material_requisition=[]) {
       $requisition = $this->getRequisition($requisitionId);
       if ($material_requisition) {
           $materialRequisition = $this->materialRequisitionRepository->getMaterialRequisitionOne($requisition,$material_requisition);

           return $this->invoicesRequisitionRepository->deliveryMaterialsRequisition($requisition,$materialRequisition);
       }

       return $this->invoicesRequisitionRepository->deliveryMaterialsRequisitionList($requisition);
   }


   public function deliveryMasterMaterialsСonfirmed($requisitionId,$deliveryId, array $materials)
   {
       $requisition = $this->getRequisition($requisitionId);
       $delivery = $this->getInvoice($deliveryId);
        if ($delivery->getRequisition() !== $requisition) {
            abort(500,'Указанная поставка не принадлежит заявке');
        }
       $materialsen = [];
       $filesen=[];
       foreach ($materials as $material) {
           //dd($material['requisition_material'],$this->materialRequisitionRepository->getMaterial($material['requisition_material']));
           $material_req =  $this->materialRequisitionRepository->getMaterial($material['requisition_material']);

           if ($material_req->getRequisition() !== $requisition) {
                Log::info("============<<<<<<<<<<<=========================");
                Log::info("Подтверждение поставки Материалов по завяке");
                Log::info("Материал: ".$material['requisition_material']." не принадлежит заявке: ".$requisitionId);
                Log::info("=============>>>>>>>>>>>========================");
           }

           if ($material_req && $material_req->getRequisition() === $requisition && $delivery) {
               //$materialdata = $this->materialRequisitionRepository->getMaterial($material['requisition_material']);

               $materialdata  = $this->invoicesRequisitionRepository->getInvoiceMaterial($material_req,$delivery);

               if ($materialdata) {
                   if (array_key_exists('files',$material)) {
                       foreach ($material['files'] as $file) {
                           $file = $this->fileService->getHashFile($file['hash']);
                           if ($file) {
                               $filesen[] = $file;
                           }
                       }
                   }

                   $newConfirmedMaterial = [
                       'requisitionInvoiceMaterial'=>$materialdata->getId(),
                       'requisition_material_quantity'=>$materialdata->getQuantity(),
                       'quantity'=>$material['quantity'],
                       'confirmedAt'=>$material['confirmed_at'],

                   ];
                   if (array_key_exists('description',$material)) {
                       $newConfirmedMaterial['description'] = $material['description'];
                   }

                   if (count($filesen) > 0) {
                       $newConfirmedMaterial['files'] = $filesen;
                       $filesen=[];
                   }

                   $materialsen[] = $newConfirmedMaterial;

               }
           }

       }

       if (count($materialsen) == 0) {
           abort('400','Нет материалов для подтверждения заявки');
       }

       return $this->invoicesRequisitionRepository->deliveryMasterMaterialsСonfirmed($materialsen);


   }

   public function deliveryMasterMaterialsСonfirmedList($requisitionId,$deliveryId,$options=[])
   {
       $requisition = $this->getRequisition($requisitionId);
       $delivery = $this->getInvoice($deliveryId);
       if ($delivery->getRequisition() !== $requisition) {
           abort(500,'Указанная поставка не принадлежит заявке');
       }
        return $this->materialRequisitionRepository->deliveryMasterMaterialsСonfirmedList($requisition,$delivery,$options);
   }

   public function deliveryMasterListRequisition($requisitionId,$options=[])
   {
       $requisition = $this->getRequisition($requisitionId);
       return $this->invoicesRequisitionRepository->invoicesRequisitionListMaster($requisition,$options);
   }
}
