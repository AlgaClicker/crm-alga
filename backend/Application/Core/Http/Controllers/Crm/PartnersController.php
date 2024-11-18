<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Directory\PartnersServiceContract;
use Core\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Storage;

class PartnersController extends Controller
{
    private PartnersServiceContract $partnersService;

    public function __construct(PartnersServiceContract $partnersService)
    {
        $this->partnersService = $partnersService;
    }

    public function actionList(Request $request)
    {
        if ($request->get('id')) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Partner,id',
            ]);

            $bank = $this->partnersService->_getById($request->get('id'));
            return  $this->sendResponse($bank);
        }

        $list = [];
        $partner = $this->partnersService->_getAllBy([],$request->get('options'));

        return  $this->sendResponse($partner['data'],$partner['options']);
    }


    public function actionEdit(Request $request) {

        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Partner,id',
            'parent' => 'nullable|uuid|exists:Domain\Entities\Business\Directory\Partner,id',
            'name' => 'sometimes|required|string|min:5',
            'fullname' => 'sometimes|required|string|min:5',
            'address' => 'sometimes|required|string|min:5',
            'bank_accounts.*.idBank' => "sometimes|required|uuid|exists:Domain\Entities\Business\Directory\Bank,id",
            'bank_accounts.*.idAccBank' => "required_with:bank_accounts.*.idBank|uuid|exists:Domain\Entities\Business\Directory\BankAccounts,id",
            'bank_accounts.*.account' => 'required_with:bank_accounts.*.idBank|numeric|digits_between:18,20',

        ]);
        $partner = $this->partnersService->editPartner($request->all());
        return  $this->sendResponse($partner);
    }

    public function actionDelete(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Partner,id'
        ]);
        return  $this->sendResponse($this->partnersService->_delete($request->get('id')));
    }

    public function actionLoadInn(Request $request) {
        $this->validate($request, [
            'inn'=>'required|numeric|digits_between:10,12'
        ]);

        $partner = $this->partnersService->loadPartnerInn($request->get('inn'));
        return  $this->sendResponse($partner);
    }
    public function actionNew(Request $request) {
        $this->validate($request, [
            'isGroup' => 'sometimes|required|boolean',
            'inn' => 'sometimes|required_if:isGroup,false|numeric|digits_between:10,12',
            'parent' => 'nullable|uuid|exists:Domain\Entities\Business\Directory\Partner,id',
        ]);

        if ($request->get('inn') && !$request->get('isGroup')) {

            $partner = $this->partnersService->newPartnerInn($request->get('inn'),$request->get('parent'));
            return  $this->sendResponse($partner);
        }

        $this->validate($request, [
            'name' => 'required|string|min:5',
            'fullname' => 'sometimes|required|string|min:5',
            'address' => 'sometimes|required|string|min:5',
       ]);
        $partner = $this->partnersService->_create($request->all());
        return  $this->sendResponse($partner);
    }

    public function actionSearch(Request $request) {

        return  $this->sendResponse($this->partnersService->_search($request->all()));
    }

    public function actionListBankAccount($idPartner,Request $request) {
        return  $this->sendResponse($this->partnersService->listBankAccount($idPartner));
    }

    public function actionAddBankAccount($idPartner,Request $request) {
        $this->validate($request, [
            'idBank' => "required|uuid|exists:Domain\Entities\Business\Directory\Bank,id",
            'idAccBank' => "sometimes|required|uuid|exists:Domain\Entities\Business\Directory\BankAccounts,id",
            'account' => 'required|numeric|digits_between:18,25',
        ]);
        return  $this->sendResponse($this->partnersService->addBankAccount($idPartner,$request->all()));
    }
    public function actionDeleteBankAccount($idPartner,Request $request) {
        $this->validate($request, [
            'id' => "required|uuid|exists:Domain\Entities\Business\Directory\PartnerBankAccounts,id",
        ]);
        return  $this->sendResponse($this->partnersService->deleteBankAccount($idPartner,$request->get('id')));
    }

}
