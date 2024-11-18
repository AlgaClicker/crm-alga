<?php

namespace Core\Http\Controllers\Admin;

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
        $banki = $this->bankService->_getAll();
        return  $this->sendResponse($banki);
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
