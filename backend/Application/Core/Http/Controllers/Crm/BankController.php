<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Directory\BankServiceContract;
use Core\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Storage;

class BankController extends Controller
{
    private BankServiceContract $bankService;

    public function __construct(BankServiceContract $bankService)
    {
        $this->bankService = $bankService;
    }

    public function actionList(Request $request)
    {
        if (array_key_exists('bik', $request->all())) {
            $this->validate($request, [
                'bik' => 'required|numeric|digits_between:3,10',
            ]);
            $bank = $this->bankService->_getByContext(['bik'=>$request->get('bik')]);
            return  $this->sendResponse($bank);
        }
        if (array_key_exists('id', $request->all())) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Bank,id',
            ]);
            $bank = $this->bankService->_getById($request->get('id'));
            return  $this->sendResponse($bank);
        }


        $banki = $this->bankService->_getAllBy([],$request->get('options'));
        return  $this->sendResponse($banki['data'],$banki['options']);
    }

    public function actionSearch(Request $request) {

        return  $this->sendResponse($this->bankService->_search($request->all()));
    }

    public function actionNew(Request $request) {
        $this->validate($request, [
            'parent' => 'required|int|min:0',
            'level' => 'required|int|min:0',
            'name' => 'required|string|min:4',
            'isGroup' => 'required|boolean',
        ]);

        if (!$request->isGroup) {
            $this->validate($request, [
                'parent' => 'required|int|min:0',
                'level' => 'required|int|min:0',
                'fullname' => 'required|string|min:4',
                'address' => 'required|string',
                'bik' => 'required|integer|min:1',
                'corset' => 'required|integer|min:1',
            ]);
        }
        $bank = $this->bankService->_create($request->all());
        return  $this->sendResponse($bank);
    }

    public function actionDelete($idBank) {

    }

    public function actionLoadCbr(Request $request) {
        $key = array_key_first($request->file());
        $file=$request->file()[$key];
        $file = $file->store('.');
        //$file = Storage::path($file);

        $result = $this->bankService->loadBankiCbr($file);
        return  $this->sendResponse($result);
    }


    public function actionEdit($idBank,Request $request) {

    }
}
