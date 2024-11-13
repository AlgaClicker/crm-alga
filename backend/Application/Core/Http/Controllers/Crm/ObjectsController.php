<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Crm\ObjectsServiceContracts;

class ObjectsController extends Controller
{
    private ObjectsServiceContracts $objectsService;
    public function __construct(
        ObjectsServiceContracts $objectsService
    ) {
        $this->objectsService = $objectsService;
    }

    public function actionNew(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|min:8',
            'address' => 'required|string|min:5',

        ]);
        $newObject = $this->objectsService->newObject($request->all());
        return  $this->sendResponse($newObject);
    }

    public function actionShow($objectId,Request $request) {

        //$request->request->add(['id' => $objectId]);
        //$request->attributes->add(['id' => $objectId]);
        $request->query->add(['id' => $objectId]);

        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Objects,id'
        ]);
        $Object = $this->objectsService->getObject($objectId);
        return  $this->sendResponse($Object);
    }

    public function actionList(Request $request) {
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }

        if ($request->get('id')) {
            $this->validate($request, [
                'id' => 'required|exists:Domain\Entities\Business\Objects\Objects,id'
            ]);
            $Object = $this->objectsService->getObject($request->get('id'));
            return  $this->sendResponse($Object);
        }

        $listObject = $this->objectsService->listObjects($request->all());
        return  $this->sendResponse($listObject['data'],$listObject['options']);
    }

    public function actionEdit(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:Domain\Entities\Business\Objects\Objects,id',
            'name' => 'required|string|min:3',
            'address' => 'required|string|min:3',
            'accounts.*' => 'required|uuid',
            'responsible.*' => 'required|uuid',
            'files.*' => 'nullable|string',

        ]);

        $Object = $this->objectsService->_updateById($request->get('id'),$request->all());
        return  $this->sendResponse($Object);
    }

    public function actionDelete(Request $request) {
        $this->validate($request, [
            'objectId' => 'required|exists:Domain\Entities\Business\Objects\Objects,id',
        ]);

        $listObject = $this->objectsService->deleteobject($request->objectId);
        return  $this->sendResponse($listObject );
    }


}
