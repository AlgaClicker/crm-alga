<?php

namespace Core\Http\Controllers\Crm;
use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Domain\Contracts\Services\Crm\MasterServiceContract;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Collection;

class MasterController extends Controller
{
    private MasterServiceContract $masterService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(MasterServiceContract $masterService)
    {
        $this->masterService = $masterService;
    }

    public function getListMySpecifications(Request $request) {
       $data =  $this->masterService->getListMySpecifications($request->get('optinos'));
        return  $this->sendResponse($data);
    }

    public function getListMaterialsSpecification($specificationId) {
        $data =  $this->masterService->getListMaterialsSpecification($specificationId);
        return  $this->sendResponse($data);
    }

    public function newRequisitionFixed($requisitionId) {
        return  $this->sendResponse($this->masterService->requisitionFixed($requisitionId));
    }

    public function newRequisitionUnFixed($requisitionId) {
        return  $this->sendResponse($this->masterService->requisitionUnFixed($requisitionId));
    }



    public function deleteRequisitionSpecificationMaster(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
        ]);

        return  $this->sendResponse($this->masterService->requisitionDelete($request->get('id')));
    }

    public function editRequisitionSpecificationMaterials(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Master\Requisition,id',
            'end_at' => 'required|date|after:now',
            'materials.*.delete' => 'nullable|boolean',
            'materials.*.specificationMaterial.id'=>'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id',
            'materials.*.description'=>'sometimes|string',
            'materials.*.quantity' => 'sometimes|required|numeric',

        ]);
        return  $this->sendResponse($this->masterService->requisitionEdit($request->get('id'),$request->all()));
    }

    public function newRequisitionSpecificationMaterials($specificationId,Request $request) {
        $this->validate($request, [
            'end_at' => 'required|date|after:now',
            'description' => 'sometimes|string|max:3000',
            'materials.*.material_specification_id'=>'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id',
            'materials.*.description'=>'sometimes|string|max:1000',
            'materials.*.quantity' => 'required|numeric|min:1',
        ]);

        $dateEnd = new \DateTimeImmutable($request->get('end_at'));
        $data =  $this->masterService->newRequisitionMaterials(
            $specificationId,
            $dateEnd,
            $request->get('materials'),
            $request->get('description')
        );
        return  $this->sendResponse($data);
    }

    public function showByIdRequisitionOther($requisitionId) {
        return  $this->sendResponse($this->masterService->getOneRequisition($requisitionId));
    }

    public function newRequisitionOther(Request $request) {
        $this->validate($request, [
            'end_at' => 'required|date|after:now',
            'description' => 'sometimes|string',
            'specificationId'=>'sometimes|required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'materials.*.name'=>'required|string|min:3',
            'materials.*.unit.code' => 'required|numeric|min:1|max:10000|exists:Domain\Entities\Utils\Units,code',
            'materials.*.description'=>'string',
            'materials.*.quantity' => 'required|numeric|min:1',
            'materials.*.material.id'=> 'sometimes|uuid|exists:Domain\Entities\Business\Directory\Material,id',
        ]);
        return $this->sendResponse($this->masterService->addRequisitionOther($request->all()));
    }

    public function masterRequisitionShowById($requisitionId,Request $request) {
        return $this->sendResponse($this->masterService->getOneRequisition($requisitionId));
    }

    public function listRequisitionSpecificationMaterials(Request $request) {
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }

        $listRequisition = $this->masterService->getAllByRequisition($request->get('options'));
        return $this->sendResponse($listRequisition['data'], $listRequisition['options']);
    }

    public function listRequisition(Request $request) {
        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }
        $requisition=$this->masterService->getAllByRequisition($request->get('options'));
        return  $this->sendResponse($requisition['data'],$requisition['options']);
    }
}
