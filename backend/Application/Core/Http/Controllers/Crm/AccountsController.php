<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\AccountServiceContracts;
use Core\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Storage;

class AccountsController extends Controller
{
    private AccountServiceContracts $accountService;

    public function __construct(AccountServiceContracts $accountService)
    {
        $this->accountService = $accountService;
    }

    public function actionList(Request $request)
    {

        if (array_key_exists('id', $request->all())) {
            $this->validate($request, [
                'id' => 'sometimes|required|uuid|exists:Domain\Entities\Subscriber\Account,id'
            ]);
            return  $this->sendResponse( $this->accountService->getAccountMyCompnay($request->get('id')));
        }


        $accounts = $this->accountService->_getAllBy([],$request->get('options'));
        return  $this->sendResponse($accounts['data'],$accounts['options']);

    }

    public function actionListAccountRoles(Request $request) {
        $accounts = $this->accountService->getCompnayListRole();
        return  $this->sendResponse($accounts);
    }
    public function actionListRoles(Request $request)
    {
        $this->validate($request, [
            'role' => 'required|string|exists:Domain\Entities\Subscriber\Roles,service'
        ]);

        $accounts = $this->accountService->getAccountsCompanyListRole($request->get('role'));
        return  $this->sendResponse($accounts);
    }

    public function actionEditAccount(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Subscriber\Account,id',
            'email' => 'nullable|email|unique:Domain\Entities\Subscriber\Account,email',
            'email_confirmation' => 'required_with:email|same:email|email',
            'phone_number' => 'nullable|numeric|digits:11',
            'username' => 'missing',
            'token' => 'missing',
            'rememberToken' => 'missing',
            'password' => 'nullable|string|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
        ]);
        $account = $this->accountService->updateAccount($request->get('id'),$request->all());

        return  $this->sendResponse($account);
    }


    public function actionHelpAccount(Request $request) {
        if (array_key_exists('id', $request->all())) {
            $this->validate($request, [
                'id' => 'sometimes|required|uuid|exists:Domain\Entities\Subscriber\Account,id'
            ]);
            return  $this->sendResponse( $this->accountService->getAccountMyCompnay($request->get('id')));
        }


        $accounts = $this->accountService->_getAllBy([],$request->get('options'));
        return view('help.account',["accounts"=>$accounts['data'],"options"=>$accounts['options']]);
    }

}
