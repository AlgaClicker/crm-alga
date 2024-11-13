<?php
namespace Core\Http\Controllers\Buisness;

use Core\Http\Controllers\Controller;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Business\BusinessServiceContracts;
use Illuminate\Http\Request;


class AccountsController extends Controller
{
    private BusinessServiceContracts $buisnessService;
    private AccountServiceContracts $accountService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        BusinessServiceContracts $buisnessService,
        AccountServiceContracts $accountService
    ){
        $this->buisnessService = $buisnessService;
        $this->accountService = $accountService;
    }

    public function actionIndex(Request $request, $idAccount=null) {



        if ($request->all() && array_key_exists('role',$request->all())) {
            $this->validate($request, [
                'role' => 'required|exists:Domain\Entities\Subscriber\Roles,service'
            ]);
        }

        if ($idAccount) {
            $allAccounts =  $this->accountService->getOne($idAccount);
        } else {
            $allAccounts = $this->accountService->getAll();
        }

        return  $this->sendResponse($allAccounts);
    }

    public function actionHistory($idAccount){
        $accountHistoruy =  $this->accountService->actionHistory($idAccount);
        return  $this->sendResponse($accountHistoruy);
    }

    public function online() {
        return  $this->sendResponse($this->accountService->checkOnline());
    }

    public function actionListRoles(){
        $allAccountsRoles = $this->accountService->getAllRoles();
        return  $this->sendResponse($allAccountsRoles);

    }

    public function actionGetRole($idRole) {
        $role =  $this->accountService->getRole($idRole);
        return  $this->sendResponse($role);
    }

    public function actionUpdateRole($idRole,Request $request){
        //$role =  $this->accountService->updateRole($idRole, $request->json());
        $role =  $this->accountService->getRole($idRole);
        return $this->sendResponse($this->accountService->getAllRoles());
    }

    public function actionAddRoles(Request $request){


        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $newCompany =  $this->accountService->createRole($request->all());
        return  $this->sendResponse($newCompany);


    }

    public function actionSave($idAccount, Request $request) {
        $this->validate($request, [
            'username' => 'required|exists:Domain\Entities\Subscriber\Account,username',
            'password' => 'nullable|size:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
            'roles' => 'required|exists:Domain\Entities\Subscriber\Roles,service',
        ]);
        $saveAccount =  $this->accountService->saveAccount($idAccount,$request->all());


        return  $this->sendResponse($saveAccount);
    }

    public function actionNewAccount(Request $request) {
        $this->validate($request, [
            'username' => 'required|unique:Domain\Entities\Subscriber\Account,username',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
            'email' => 'required|unique:Domain\Entities\Subscriber\Account,email',
            'roles' => 'required|exists:Domain\Entities\Subscriber\Roles,service',
        ]);
        $newAccount =  $this->accountService->newAccount($request->all());
        return  $this->sendResponse($newAccount);
    }

    public function actionListCompanyAccount($idAccount) {
        $company =  $this->buisnessService->getListCompanyAccount($idAccount);
        return $this->sendResponse($company);
    }

    public function actionAccountDel($idAccount) {
        $allAccountsRoles = $this->accountService->deleteAccount($idAccount);
        return  $this->sendResponse($allAccountsRoles);
    }


    public function actionOption() {
        $allOptions = $this->accountService->optionsAccount();

        return  $this->sendResponse($allOptions);
    }

    public function actionOptionSet(Request $request)
    {
        $allOptions = $this->accountService->optionsAccountSet($request->collect());
        return $this->sendResponse($allOptions);
    }
}
