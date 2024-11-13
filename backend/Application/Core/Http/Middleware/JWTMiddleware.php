<?php

namespace Core\Http\Middleware;

use Closure;
use Illuminate\Contracts\Auth\Factory as Auth;
use Core\Exceptions\ApplicationException;
use Illuminate\Http\Request;
use Domain\Contracts\Services\AccountServiceContracts;
use Illuminate\Support\Facades\Log;
use Kreait\Laravel\Firebase\Facades\Firebase;

class JWTMiddleware
{
    protected Auth $auth;
    protected AccountServiceContracts $accountService;
    public function __construct(Auth $auth,AccountServiceContracts $accountService)
    {
        $this->accountService = $accountService;
        $this->auth = $auth;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param null $guard
     * @return mixed
     * @throws ApplicationException
     */
    public function handle(Request $request, Closure $next, $guard=null)
    {

        if ($request->cookie("token")) {
            $account = $this->accountService->checkToken($request->cookie("token"));
            if ($account) {
                $this->auth->guard($guard)->login($account);
                return $next($request);
            }
        }


        $authHeader = $request->header('Authorization');
        list($jwt) = (sscanf($authHeader, 'Bearer %s'));

        $account = $this->accountService->checkToken($jwt);

        if (!$account && env('FIREBASE_USE',"false") == 'true' && $jwt) {

            try {
                $auth = Firebase::auth();

                    $verify = $auth->verifyIdToken($jwt);

                    if ($verify){
                        $account_id = $verify->claims()->get('user_id');
                        $account =  $this->accountService->getAccountUuid($account_id);
                    }
            } catch (\Kreait\Firebase\Exception\AuthException $e) {
                abort(401,'Unauthorized');
            }
        }

        //LOG::info("CLASS JWTMiddleware JWT:".$jwt);
        if (!$account) {
            abort(401,'Unauthorized');
        }
        if ($account && $jwt){
            $this->auth->guard($guard)->login($account);

        }

        if ($this->auth->guard($guard)->guest()) {
            abort(401,'Unauthorized');
            throw new ApplicationException('Unauthorized',401);
        }

        return $next($request);
    }
}
