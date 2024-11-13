<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Directory\RecipientServiceContract;


class RecipientController extends Controller
{
    private RecipientServiceContract $partnerService;

    public function __construct(RecipientServiceContract $partnerService)
    {
        $this->partnerService = $partnerService;
    }

    public function actionList(Request $request)
    {
        return  $this->sendResponse($request);
    }
}
