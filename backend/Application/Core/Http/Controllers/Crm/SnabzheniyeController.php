<?php

namespace Core\Http\Controllers\Crm;
use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Document\WorkflowServiceContract;
use Domain\Contracts\Services\Crm\RequisitionServiceContract;

class SnabzheniyeController extends Controller
{
    private RequisitionServiceContract $requisitionService;

    public function __construct(RequisitionServiceContract $requisitionService)
    {
        $this->requisitionService = $requisitionService;
    }

    public function requisitionListNoManager(Request $request) {
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }
        $requisition = $this->requisitionService->getRequisitionListNoManager($request->get('options'));

        return  $this->sendResponse($requisition['data'],$requisition['options']);

    }
    public function requisitionListMy(Request $request) {
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }
        $requisitions = $this->requisitionService->getRequisitionListMy($request->get('options'));
        return  $this->sendResponse($requisitions['data'],$requisitions['options']);
    }


    public function requisitionSetManager($requisitionId) {
        return  $this->sendResponse($this->requisitionService->setManager($requisitionId));
    }

    public function snabzheniyeRequisitionInfoManager($requisitionId) {
        return $this->sendResponse($this->requisitionService->getRequisition($requisitionId));
    }
}
