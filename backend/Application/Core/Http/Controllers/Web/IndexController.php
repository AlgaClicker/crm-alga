<?php

namespace Core\Http\Controllers\Web;

use Auth;
use Domain\Contracts\Services\AccountServiceContracts;
use Domain\Contracts\Services\Business\BusinessServiceContracts;
use Illuminate\Http\Request;
use Core\Exceptions\ApplicationException;
use Log;
use Core\Http\Controllers\Controller;



class IndexController extends Controller
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
        $this->middleware('api', ['except' => ['login','registration']]);
    }

    public function index() {

    }
}
