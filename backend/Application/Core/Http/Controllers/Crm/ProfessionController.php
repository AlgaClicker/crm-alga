<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Directory\ProfessionServiceContract;
use Core\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Storage;

class ProfessionController extends Controller
{
    private ProfessionServiceContract $professionService;

    public function __construct(ProfessionServiceContract $professionService)
    {
        $this->professionService = $professionService;
    }

    public function actionList(Request $request)
    {

        $options=$request->get('options');

        if ($request->get('id')) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Profession,id',

            ]);
            $profession = $this->professionService->_getById($request->get('id'));
            return $this->sendResponse($profession);
        }

        $profession = $this->professionService->_getAllBy([], $options);
        return $this->sendResponse($profession['data'], $profession['options']);
    }

    public function actionSearch(Request $request) {

        return  $this->sendResponse($this->professionService->_search($request->all()));
    }


    public function actionNew(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|min:3',
            'fullname' => 'required|string|min:3',
        ]);

        $bank = $this->professionService->_create($request->all());
        return  $this->sendResponse($bank);
    }

    public function actionDelete(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Profession,id',

        ]);
        $profession = $this->professionService->_delete($request->get('id'));
        return $this->sendResponse($profession);
    }


    public function actionEdit(Request $request) {
        $this->validate($request, [
            'name' => 'required|string|min:3',
            'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Profession,id',

        ]);
        return  $this->sendResponse($this->professionService->_updateById($request->get("id"),$request->all()));
    }
}
