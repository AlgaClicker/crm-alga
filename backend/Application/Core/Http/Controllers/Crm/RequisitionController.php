<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Domain\Services\Business\SpecificationService;
use Illuminate\Http\Request;
use Domain\Contracts\Services\Crm\MasterServiceContract;
use Domain\Contracts\Services\Crm\RequisitionServiceContract;
use Illuminate\Support\Facades\Validator;


class RequisitionController extends Controller
{
    private MasterServiceContract $masterService;
    private RequisitionServiceContract $requisitionService;
    private SpecificationsServiceContracts $specificationsService;

    public function __construct(RequisitionServiceContract $requisitionService,SpecificationsServiceContracts $specificationsService)
    {
        $this->requisitionService = $requisitionService;
        $this->specificationsService = $specificationsService;

    }


    public function getRequisition($requisitionId) {
        return  $this->sendResponse($this->requisitionService->getRequisition($requisitionId));
    }

    public function listRequisition(Request $request) {
        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
        }
        $requisition=$this->requisitionService->listRequisition($request->get('options'));
        return  $this->sendResponse($requisition['data'],$requisition['options']);
    }

    public function listMaterialRequisition($requisitionId,Request $request) {
        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
        }
        $requisition=$this->requisitionService->listMaterialRequisition($requisitionId,$request->get('options'));

        return  $this->sendResponse($requisition['data'],$requisition['options']);
    }


    public  function  listRequisitionOther(Request $request) {
        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
        }
        $requisition=$this->requisitionService->listRequisitionOther($request->get('options'));

        return  $this->sendResponse($requisition['data'],$requisition['options']);
    }


    public function editRequisitionOther(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
            'end_at' => 'required|date|after:now',
            'description'=>'null|string|min:1|max:500'
        ]);
        return  $this->sendResponse($this->requisitionService->editRequisitionOther(
            $request->get("id"),
            $request->get("end_at"),
            $request->get("description"),
        ));
    }

    public function deleteRequisitionOther(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
        ]);
        return  $this->sendResponse($this->requisitionService->deleteRequisitionOther($request->get('id')));
    }

    public function chatListMessageRequisition($requisitionId, Request $request) {
        return  $this->sendResponse($this->requisitionService->getListMessagesChatRequisition($requisitionId,$request->get('options')));
    }

    public function chatSendMessageRequisition($requisitionId, Request $request) {
        $this->validate($request, [
            'message' => 'required|string|min:1',
            'files.*' => 'nullable|string|exists:Domain\Entities\Services\Files,hash',
        ]);
        return  $this->sendResponse($this->requisitionService->sendMessagesChatRequisition($requisitionId,$request->get('message'),$request->get('files')));
    }

    public function requisitionCleanManager($requisitionId) {
        return  $this->sendResponse($this->requisitionService->cleanManager($requisitionId));
    }

    public function  addMaterialRequisitionSpecification($requisitionId,Request $request) {

        $this->validate($request, [
            'unit.code' => 'sometimes|required|int|min:1|max:10000|exists:Domain\Entities\Utils\Units,code',
            'material.id'=>'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id',
            'description'=>'nullable|string|min:3',
            'name'=>'sometimes|required|string|min:3',
            'quantity' => 'required|numeric|min:0.0001',

        ]);

        return  $this->sendResponse($this->requisitionService->newMaterialSpecificationRequisition($requisitionId,$request->all()));
    }
    public function  addMaterialRequisitionOther($requisitionId,Request $request) {

        $this->validate($request, [
            'unit.code' => 'sometimes|required|int|min:1|max:10000|exists:Domain\Entities\Utils\Units,code',
            'material.id'=>'sometimes|required|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            'description'=>'nullable|string|min:1',
            'name'=>'sometimes|required|string|min:1',
            'quantity' => 'required|numeric|min:0.0001',

        ]);

        return  $this->sendResponse($this->requisitionService->newMaterialRequisitionOther($requisitionId,$request->all()));
    }

    public function  editMaterialRequisitionOther($requisitionId, $materialRequisitionId,Request $request) {
        $this->validate($request, [
            'unit.code' => 'sometimes|required|int|min:1|max:10000|exists:Domain\Entities\Utils\Units,code',
            'material.id'=>'sometimes|required|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            'description'=>'nullable|string|min:3',
            'name'=>'sometimes|required|string|min:3',
            'quantity' => 'required|numeric|min:0.0001',

        ]);
        return  $this->sendResponse($this->requisitionService->editMaterialRequisitionOther($requisitionId,$materialRequisitionId,$request->all()));
    }

    public function deleteMaterialRequisitionOther($requisitionId, $materialRequisitionId) {

        return  $this->sendResponse($this->requisitionService->deleteMaterialRequisitionOther($requisitionId,$materialRequisitionId));
    }

    public function snabzheniyeRequisitionWork($requisitionId, Request $request) {
        return $this->sendResponse($this->requisitionService->snabzheniyeRequisitionWork($requisitionId,$request->all()));
    }

    public function setMaterialRequisitionMaterialDirectoryOther($requisitionId,$materialRequisitionId,Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Material,id',
        ]);
        return $this->sendResponse($this->requisitionService->setMaterialRequisitionDirectory($requisitionId,$materialRequisitionId,$request->get('id')));
    }

    public function requisitionSetManager($requisitionId, Request $request) {
        $this->validate($request, [
            'account' => 'required|uuid|exists:Domain\Entities\Subscriber\Account,id',
        ]);
        return $this->sendResponse($this->requisitionService->setManager($requisitionId,$request->get('account')));
    }

    public function requisitionCalculation($requisitionId) {
        $dateAt = new \DateTimeImmutable('now');
        return $this->sendResponse($this->requisitionService->requisitionCalculation($requisitionId,$dateAt));
    }
    public function deleteRequisitionInvoice($requisitionId,Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Document\Requisition\Invoice,id',
        ]);
        return $this->sendResponse($this->requisitionService->deleteRequisitionInvoice($requisitionId,$request->get('id')));
    }

    public function unSetMaterialRequisitionMaterialDirectoryOther($requisitionId,$materialRequisitionId) {
        return $this->sendResponse($this->requisitionService->unSetMaterialRequisitionDirectory($requisitionId,$materialRequisitionId));
    }

    public function snabzheniyeRequisitionWorkListInvoices($requisitionId,Request $request) {
        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
        }
        $requisition=$this->requisitionService->invoicesRequisitionList($requisitionId,$request->get('options'));

        return  $this->sendResponse($requisition['data'],$requisition['options']);
    }

    public function InvoicesListManager(Request $request)
    {
        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
        }
        $requisition=$this->requisitionService->invoicesListManager($request->get('options'));

        return  $this->sendResponse($requisition['data'],$requisition['options']);

    }
    public function requisitionMaterialCancel($requisitionId, Request $request) {
        $this->validate($request, [
            'description' => 'required|string|min:1',
        ]);

        return  $this->sendResponse($this->requisitionService->cancelRequisition($requisitionId,$request->get('description')));

    }

    public function snabzheniyeRequisitionWorkAdd($requisitionId, Request $request) {
        if (count($request->all()) == 0) {
            abort('400','Пустой запрос');
        }
        $req['requisitionId'] = $requisitionId;
        $request->request->add($req);
       $this->validate($request, [
            'requisitionId' => 'required|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
            'delivery_at'=>'required|date|after:now',
            'contract' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id',
            'stock'=>'sometimes|required|uuid|exists:Domain\Entities\Business\Company\Stock,id',
            'specification' => 'null|sometimes|required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'materials.*.id' => 'required|uuid|exists:Domain\Entities\Business\Master\RequisitionMaterials,id',
            'materials.*.price' => 'required|numeric|min:0,001',
            'materials.*.quantity' => 'required|numeric|min:0,001',
        ]);

        return $this->sendResponse($this->requisitionService->snabzheniyeRequisitionWorkAdd($requisitionId,$request->all()));
    }

    public function showMaterialRequisition($requisitionId, $materialRequisitionId) {
        return $this->sendResponse($this->requisitionService->showMaterialRequisition($requisitionId,$materialRequisitionId));
    }

    public function requisitionSetStatus($requisitionId,Request $request) {
        $this->validate($request, [
            'status'=>'required|string|in:manage,canceled,inprogress,completed,agreement,new,draft'
        ]);
    }

    public function deliveryMasterMaterialsСonfirmedList($requisitionId,$deliveryId,Request $request)
    {
        $req['requisitionId'] = $requisitionId;
        $req['deliveryId'] = $deliveryId;

        $request->request->add($req);

        $this->validate($request,[
            'requisitionId' => 'required|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
            'deliveryId' => 'required|uuid|exists:Domain\Entities\Business\Document\Requisition\Invoice,id',
        ]);

        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
        }
            $result = $this->requisitionService->deliveryMasterMaterialsСonfirmedList($requisitionId,$deliveryId,$request->get('options'));

        return  $this->sendResponse($result['data'],$result['options']);
    }

    public function deliveryMasterMaterialsСonfirmed($requisitionId,$deliveryId,Request $request) {

        $req['requisitionId'] = $requisitionId;
        $req['deliveryId'] = $deliveryId;

        $request->request->add($req);

        $this->validate($request,[
            'requisitionId' => 'required|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
            'deliveryId' => 'required|uuid|exists:Domain\Entities\Business\Document\Requisition\Invoice,id',
            'materials' => 'required|array',
            'materials.*.confirmed_at'=>'required|date',
            'materials.*.requisition_material' => 'required|uuid|exists:Domain\Entities\Business\Master\RequisitionMaterials,id',
            'materials.*.quantity' => 'required|numeric|min:0,0001',
            'materials.*.description' => 'nullable|sometimes|string',
            'materials.*.files.*.hash' => 'nullable|sometimes|required|string|exists:Domain\Entities\Services\Files,hash',
        ]);
        return  $this->sendResponse($this->requisitionService->deliveryMasterMaterialsСonfirmed($requisitionId,$deliveryId,$request->get('materials')));
    }

    public function specificationRequisitionInvoice($specificationId, Request $request)
    {
        $req['specificationId'] = $specificationId;
        $validator = Validator::make($req, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);

        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }
        $specification = $this->specificationsService->getSpecificationOnly($specificationId);
        $requisition=$this->requisitionService->specificationInvoicesRequisitionList($specification,$request->get('options'));

        return  $this->sendResponse($requisition['data'],$requisition['options']);
    }


    public function deliveryMaterials($requisitionId, Request $request) {
        $this->validate($request, [
            'material_requisition.id' => 'sometimes|required|uuid|exists:Domain\Entities\Business\Master\RequisitionMaterials,id',
        ]);

        return $this->sendResponse($this->requisitionService->deliveryMaterials($requisitionId,$request->get('material_requisition')));
    }

    public function deliveryMasterListRequisition($requisitionId, Request $request)
    {
        $req['requisitionId'] = $requisitionId;
        $request->request->add($req);
        $this->validate($request,[
            'requisitionId' => 'required|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
        ]);
        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }
        return $this->sendResponse($this->requisitionService->deliveryMasterListRequisition($requisitionId,$request->get('options')));
    }

    public function deliveryRequisitionProgress($requisitionId,$deliveryId, Request $request)
    {
        $req['requisitionId'] = $requisitionId;
        $req['deliveryId'] = $deliveryId;

        $request->request->add($req);

        $this->validate($request,[
            'requisitionId' => 'required|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
            'deliveryId' => 'required|uuid|exists:Domain\Entities\Business\Document\Requisition\Invoice,id'
        ]);

        return $this->sendResponse($this->requisitionService->deliveryRequisitionProgress($requisitionId,$deliveryId));
    }

}
