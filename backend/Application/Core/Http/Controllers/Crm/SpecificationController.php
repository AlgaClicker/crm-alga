<?php

namespace Core\Http\Controllers\Crm;

use Domain\Contracts\Services\Crm\SpecificationsServiceContracts;
use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class SpecificationController extends Controller
{
    private SpecificationsServiceContracts $specificationsService;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        SpecificationsServiceContracts $specificationsService
    )
    {
        $this->specificationsService = $specificationsService;
    }

    public function showSpecificationsByObject($objectId, Request $request)
    {
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }

        $request->query->add(['objectId' => $objectId]);
        $this->validate($request, [
            'objectId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Objects,id',
        ]);
        $spec = $this->specificationsService->actualSpecificationObject($request->get('objectId'), $request->get('options'));
        return $this->sendResponse($spec['data'], $spec['options']);
    }

    public function showSpec(Request $request)
    {

        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
        }


        if ($request->get('id')) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            ]);
            $spec = $this->specificationsService->listSpec('', $request->get('id'), $request->get('options'));
            return $this->sendResponse($spec);
        } elseif ($request->get('objectId')) {

            $this->validate($request, [
                'objectId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Objects,id',
            ]);
            return $this->showSpecificationsByObject($request->get('objectId'), $request);

        } else {
            $spec = [];
        }
        return $this->sendResponse(null);
    }

    public function showSpecHistory(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);
        $spec = $this->specificationsService->actionHistory($request->get('id'));
        return $this->sendResponse($spec);
    }

    public function showSpecMaterialHistory(Request $request)
    {
        $this->validate($request, [
            'specificationMaterialId' => 'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id',
        ]);
        $materialListHistory = $this->specificationsService->historySpecificationMaterial($request->get('specificationMaterialId'));
        return $this->sendResponse($materialListHistory);
    }

    public function deleteSpec(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id'
        ]);
        $result = $this->specificationsService->deleteSpecification($request->get('id'));
        return $this->sendResponse($result);
    }

    public function loadNewSpec(Request $request)
    {
        $this->validate($request, [
            'hashfile' => 'required|string',
        ]);
        $hashFile = $request->get('hashfile');

        $spec = $this->specificationsService->loadNew($hashFile);

        return $this->sendResponse($spec);

        //$newCompany =  $this->buisnessService->createCompany($request->all());
        //return  $this->sendResponse($newCompany);
    }

    public function editSpec(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'name' => 'required|string',
            'description' => 'sometimes|required|string',
            'contract.id' => 'sometimes|required|uuid|exists:Domain\Entities\Business\Document\Contracts,id',
            'responsibles.*.id' => 'nullable|uuid|exists:Domain\Entities\Subscriber\Account,id',
            'contracts.*.id' => 'nullable|uuid|exists:Domain\Entities\Business\Document\Contracts,id'
        ]);
        $specifications = $this->specificationsService->editSpecification($request->all());
        return $this->sendResponse($specifications);

    }

    public function fixSpec(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);
        $specifications = $this->specificationsService->fixSpec($request->get('id'), true);
        return $this->sendResponse($specifications);
    }

    public function unfixSpec(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);
        $specifications = $this->specificationsService->fixSpec($request->get('id'), false);
        return $this->sendResponse($specifications);

    }


    public function newSpec(Request $request)
    {

        $this->validate($request, [
            'objectId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Objects,id',
            'parent' => 'nullable|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'contract.id' => 'sometimes|required|uuid|exists:Domain\Entities\Business\Document\Contracts,id',
            'name' => 'required|string',
            'description' => 'required|string',
            'responsibles.*.id' => 'nullable|uuid|exists:Domain\Entities\Subscriber\Account,id',
            'contracts.*.id' => 'nullable|uuid|exists:Domain\Entities\Business\Document\Contracts,id'
        ]);

        $specification = $this->specificationsService->newSpecification($request->all());

        return $this->sendResponse($specification);

    }

    public function showSpecMaterial(Request $request)
    {

        if ($request->get('id')) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id'
            ]);
            return $this->sendResponse($this->specificationsService->getSpecificationMaterial($request->get('id')));
        }

        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id'
        ]);

        return $this->sendResponse($this->specificationsService->listSpecificationMaterial($request->get('specificationId')));

    }

    public function specificationSetContract($specificationId, Request $request)
    {
        $request->request->add(['specificationId' => $specificationId]);
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'contract_id' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id'
        ]);
        return $this->sendResponse($this->specificationsService->setSpecificationContract($request->get('specificationId'), $request->get('contract_id')));
    }

    public function specificationSetContractId($specificationId, $contractId, Request $request)
    {
        $request->request->add(['specificationId' => $specificationId]);
        $request->request->add(['contract_id' => $contractId]);
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'contract_id' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id',
        ]);
        return $this->sendResponse($this->specificationsService->setSpecificationContract($request->get('specificationId'), $request->get('contract_id')));
    }

    public function specificationGetContract($specificationId, Request $request)
    {
        $request->request->add(['specificationId' => $specificationId]);
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id'
        ]);
        if ($request->get('options') && gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',

            ]);
        }
        return $this->sendResponse($this->specificationsService->getSpecificationContract($request->get('specificationId'), $request->get('options')));
    }

    public function specificationCleanContract($specificationId, Request $request)
    {
        $request->request->add(['specificationId' => $specificationId]);
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id'
        ]);
        return $this->sendResponse($this->specificationsService->removeSpecificationContract($request->get('specificationId')));
    }

    public function showSpecIdMaterialId($specificationId, $idMaterial)
    {
        return $this->sendResponse($this->specificationsService->getSpecificationMaterial($idMaterial));
    }

    public function showSpecIdMaterial($specificationId, Request $request)
    {
        return $this->sendResponse($this->specificationsService->listSpecificationMaterial($specificationId));
    }


    public function showSpecIdMaterialNew($specificationId, Request $request)
    {
        $request->request->add(['specificationId' => $specificationId]);
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'fullname' => 'required|min:3',
            'code' => 'nullable|sometimes|required_if:is_group,false|string|min:1|exists:Domain\Entities\Utils\Units,code',
            'index' => 'sometimes|required|numeric|min:1',

            'is_group' => 'sometimes|required|boolean',
            'unit_code' => 'sometimes|required_if:is_group,false|string|min:1|exists:Domain\Entities\Utils\Units,code',
            'material' => 'nullable|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            'quantity' => 'required_if:isGroup,false|numeric|min:0',
        ]);

        $material = $this->specificationsService->newSpecificationMaterial($specificationId, $request->all());
        return $this->sendResponse($material);
    }

    public function actionSpecIdMaterialEdit($specificationId, Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id',
            'material' => 'nullable|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            'index' => 'sometimes|required|numeric|min:1',
            'code' => 'nullable|min:1',
            'fullname' => 'required|string|min:3',
            'is_group' => 'sometimes|required|boolean',
            'unit_code' => 'sometimes|required_if:is_group,false|string|exists:Domain\Entities\Utils\Units,code',
        ]);

        $material = $this->specificationsService->editSpecificationMaterial($specificationId, $request->get('id'), $request->all());
        return $this->sendResponse($material);
    }

    public function showSpecIdMaterialDelete($specificationId, Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id'
        ]);
        return $this->sendResponse($this->specificationsService->deleteSpecificationMaterial($specificationId, $request->get('id')));
    }

    public function showSpecMaterialNew(Request $request)
    {
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'fullname' => 'required|min:3',
            'code' => 'nullable|min:1',
            'index' => 'sometimes|required|numeric|min:1',
            'position' => 'nullable|numeric|between:0,999.99',
            'is_group' => 'sometimes|required|boolean',
            'unit_code' => 'sometimes|required_if:is_group,false|string|min:1|exists:Domain\Entities\Utils\Units,code',
            'material' => 'nullable|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            'quantity' => 'required_if:isGroup,false|numeric|min:0',
        ]);

        $material = $this->specificationsService->newSpecificationMaterial($request->specificationId, $request->all());
        return $this->sendResponse($material);
    }


    public function actionSpecMaterialEdit(Request $request)
    {
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'specificationMaterialId' => 'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id',
            'material' => 'nullable|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            'index' => 'sometimes|required|numeric|min:1',
            'code' => 'nullable|min:1',
            'fullname' => 'required|string|min:3',
            'is_group' => 'sometimes|required|boolean',
            'unit_code' => 'sometimes|required_if:is_group,false|string|min:1|exists:Domain\Entities\Utils\Units,code',
        ]);

        $material = $this->specificationsService->editSpecificationMaterial($request->specificationId, $request->specificationMaterialId, $request->all());
        return $this->sendResponse($material);
    }

    public function showSpecMaterialDelete(Request $request)
    {
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'specificationMaterialId' => 'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id'
        ]);
        return $this->sendResponse($this->specificationsService->deleteSpecificationMaterial($request->get('specificationId'), $request->get('specificationMaterialId')));
    }


    public function showSpecTypeWork(Request $request)
    {
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);

        return $this->sendResponse($this->specificationsService->getSpecificationTypeWorks($request->get('specificationId')));

    }

    public function showSpecTypeWorkNew(Request $request)
    {
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'number' => 'required|string',
            'nameWorks' => 'required|string',
            'quantity' => 'required|numeric',

        ]);
        $specificationTypeWorks = $this->specificationsService->newSpecificationTypeWorks($request->get('specificationId'), $request->all());
        return $this->sendResponse($specificationTypeWorks);
    }

    public function showSpecTypeWorkEdit(Request $request)
    {
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'specificationTypeWorkId' => 'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationTypeWorks,id'
        ]);
        $specification = $this->specificationsService->editSpecificationTypeWorks(
            $request->get('specificationId'),
            $request->get('specificationTypeWorkId'),
            $request->all()
        );
        return $this->sendResponse($specification);
    }

    public function showSpecReadyNoFix(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id'
        ]);
        $specification = $this->specificationsService->readySpecificationNoFix($request->get('id'));
        return $this->sendResponse($specification);
    }

    public function specificationChangeNew(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'name' => 'required|string',
            'description' => 'required|string'
        ]);
        $specification = $this->specificationsService->SpecificationChangeAdd($request->all());
        return $this->sendResponse($specification);
    }

    public function specificationChangeList(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);
        $specifications = $this->specificationsService->specificationChangeList($request->get('id'));
        return $this->sendResponse($specifications);
    }

    public function specificationAll(Request $request)
    {
        $specifications = $this->specificationsService->specificationAll($request->get('options'));
        return $this->sendResponse($specifications);
    }

    public function specificationCalculation($specificationId, Request $request)
    {
        $this->validate($request, [
            'materials.*.id' => 'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id',
            'materials.*.amount' => 'required|numeric',
            'materials.*.file' => 'nullable|string|exists:Domain\Entities\Services\Files,hash',
            'materials.*.description' => 'nullable|string|min:1',
        ]);
        $specificationMaterial = $this->specificationsService->specificationMaterialSetСalculation($specificationId, $request->get('materials'));
        return $this->sendResponse($specificationMaterial);
    }

    public function specificationMaterialSearch(Request $request)
    {
        $this->validate($request, [
            'specificationId' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'name' => 'required|string|min:3'
        ]);
        $specificationMaterial = $this->specificationsService->specificationMaterialSearch($request->get('specificationId'), $request->get('name'));
        return $this->sendResponse($specificationMaterial);
    }

    public function specificationMaterialRemnants($specificationId, Request $request)
    {

        if (array_key_exists('id', $request->all())) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\SpecificationMaterial,id',
            ]);
            $specificationMaterial = $this->specificationsService->specificationMaterialRemnants($specificationId, $request->get('id'));
        } else {
            $specificationMaterial = $this->specificationsService->specificationMaterialRemnants($specificationId);
        }

        return $this->sendResponse($specificationMaterial);
    }

    public function specificationResponsible($specificationId, Request $request)
    {
        return $this->sendResponse($this->specificationsService->specificationResponsible($specificationId));
    }

    public function specificationResponsibleAdd($specificationId, Request $request)
    {
        $this->validate($request, [
            'account_id' => 'required|uuid|exists:Domain\Entities\Subscriber\Account,id'
        ]);
        return $this->sendResponse($this->specificationsService->specificationResponsibleAdd($specificationId, $request->get('account_id')));
    }

    public function specificationResponsibleDelete($specificationId, Request $request)
    {
        $this->validate($request, [
            'account_id' => 'required|uuid|exists:Domain\Entities\Subscriber\Account,id'
        ]);
        return $this->sendResponse($this->specificationsService->specificationResponsibleDelete($specificationId, $request->get('account_id')));
    }

    public function loadSpecFile($objectId, Request $request)
    {
        $list_files_import = new Collection();
        foreach ($request->file() as $file) {
            try {
                if (gettype($file) === 'array') {
                    $list_files_import->add($this->specificationsService->importSpecFile($file['file'])) ;
                } elseif (gettype($file) === 'object') {
                    $list_files_import->add($this->specificationsService->importSpecFile($file));
                }
            } catch ( \Exception $e ) {
                Log::warning($e->getMessage());
            }
        }
        return $this->sendResponse($list_files_import->all());
    }

}
