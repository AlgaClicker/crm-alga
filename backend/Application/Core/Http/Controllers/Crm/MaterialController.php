<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Directory\MaterialServiceContract;
use Core\Exceptions\ApplicationException;


class MaterialController extends Controller
{
    private MaterialServiceContract $materialService;

    public function __construct(
        MaterialServiceContract $materialService
    ) {
        $this->materialService = $materialService;
    }

    public function actionSearch(Request $request) {

        return  $this->sendResponse($this->materialService->_search($request->all()));
    }

    public function actionNew(Request $request) {

        $this->validate($request, [
            'name' => 'required|string|min:6',
            'description' => 'nullable|string|min:6',
            'isGroup' => 'required|boolean',
            'unit_code' => 'required_if:isGroup,false|string|min:1|exists:Domain\Entities\Utils\Units,code',

            'parent'=> 'nullable|uuid|exists:Domain\Entities\Business\Directory\Material,id',
        ]);


        $material = $this->materialService->newMaterial($request->all());
        return  $this->sendResponse($material);
    }

    public function actionMaterialHistory(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Material,id',
        ]);
        $materialListHistory = $this->materialService->historyMaterial($request->all());
        return  $this->sendResponse($materialListHistory);

    }

    public function listUnits() {
        $units = $this->materialService->listUnits();
        return  $this->sendResponse($units);
    }

    public function newUnit(Request $request) {
        $this->validate($request, [
            'list.*.name' => 'required|string|min:2',
            'list.*.title' => 'required|string',
            'list.*.code' => 'required|string|min:1|max:99999',
        ]);
        $units = $this->materialService->newUnit($request->get('list'));
        return  $this->sendResponse($units);
    }


    public function actionList(Request $request) {
        if ($request->get('id')) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            ]);
            $material = $this->materialService->_getById($request->get('id'));
            return  $this->sendResponse($material);
        }

        if ($request->get('parent')) {
            $this->validate($request, [
                'parent' => 'required|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            ]);
            $material = $this->materialService->getListMaterials($request->get('parent'),$request->get('options'));
            return  $this->sendResponse($material['data'],$material['options']);
        }

        $material = $this->materialService->getListMaterials($request->get('parent'),$request->get('options'));
        return  $this->sendResponse($material['data'],$material['options']);
    }

    public function actionEdit(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            'parent' => 'nullable|uuid|exists:Domain\Entities\Business\Directory\Material,id',
            'is_group' => 'sometimes|required|boolean',
            'unit_code' => 'sometimes|required_if:isGroup,false|string|min:1|exists:Domain\Entities\Utils\Units,code',
            'name' => 'sometimes|required|string|min:3',
        ]);


        $material = $this->materialService->editMaterial($request->id,$request->all());
        return  $this->sendResponse($material);
    }

    public function actionDelete(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Material,id',
        ]);
        $material = $this->materialService->deleteMaterial($request->id);
        return  $this->sendResponse($material);
    }

}
