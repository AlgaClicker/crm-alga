<?php

namespace Core\Http\Controllers;

use Auth;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Business\BusinessServiceContracts;
use Illuminate\Http\Request;
use Core\Exceptions\ApplicationException;
use Log;

use Illuminate\Support\Facades\Validator;


class AuthController extends Controller
{
    private AccountServiceContracts $accountService;
    private BusinessServiceContracts $businessService;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(
        AccountServiceContracts $accountService,
        BusinessServiceContracts $businessService
    ){
        $this->accountService = $accountService;
        $this->businessService = $businessService;
        $this->middleware('api', ['except' => ['login','registration','registrationConfirm']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        $this->validate($request,[
            'username' => 'required|string|min:3',
            'password' => 'required|string|min:3'
        ]);

        $account = $this->accountService->login($request->get('username'),$request->get('password'));

        $data = [
            'token'=>$account->getToken(),
            'type' => "bearer",
            'expires_in' => auth()->factory()->getTTL() * 60,
            'account'=>$account,
            'company'=>$account->getCompany()
        ];

        return  $this->sendResponse($data);
    }

    public function registrationConfirm(Request $request)
    {
        $this->validate($request, [
            'k' => 'required|exists:Domain\Entities\Subscriber\Account,token',
        ]);

        return $this->sendResponse($this->accountService->registrationConfirm($request->query("k")));
    }
    public function meFirebase() {
        $this->sendResponse($this->accountService->getFirebaseAccount());
    }
     /**
     * Get the authenticated Account.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function me()
    {
        $accountId = auth()->user()->getId();

        $account = $this->accountService->getOne($accountId);
//        /dd(json_encode($account),$account);
        $accountArr = json_decode($this->serialize($account));
        //dd($accountArr);
        $accountArr->token = $account->getToken();
        $accountArr->refresh_token = $this->accountService->getRefreshToken();
        $accountArr->company = json_decode($this->serialize($account->getCompany()));
        $accountArr->options = $this->accountService->optionsAccount();
        return  $this->sendResponse($accountArr);
    }

    public function registration(Request $request) {
        $this->validate($request, [
            'username' => 'required|unique:Domain\Entities\Subscriber\Account,username',
            'password' => 'required|string|min:6',
            'password_confirmation' => 'required_with:password|same:password|min:6',
            'inn' => 'required|integer|between:1000000000,999999999999',
            'email' => 'required|unique:Domain\Entities\Subscriber\Account,email',
            'phone_number' => 'required|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
        ]);

        $newCompany = $this->businessService->registrationCompany($request->all());

        if (!$newCompany) {
            throw new ApplicationException('Ошибка регистрации новой компании',500);
        }


        return  $this->sendResponse($newCompany);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth()->logout();

        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    public function signInWithRefreshToken(Request $request)
    {
        $this->validate($request, [
            'refresh_token' => 'required|string|min:24',
        ]);
        return  $this->sendResponse($this->accountService->signInWithRefreshToken($request->get('refresh_token')));
    }


    public function option() {
        return  $this->sendResponse(auth()->user());
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => auth()->factory()->getTTL() * 60
        ]);
    }
}
