<?php

namespace Core\Http\Controllers;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Document\WorkflowServiceContract;


class DocumentsController extends Controller
{
    private WorkflowServiceContract $workflowService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(WorkflowServiceContract $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function actionListDocument(Request $request)
    {
        $uuid = '';
        if ($request->get('uuid')){
            $this->validate($request, [
                'show_all' => 'nullable|boolean',
                'uuid' => 'required_if:show_all,false|uuid|exists:Domain\Entities\Business\Document\Workflow,id',
            ]);
            $uuid = $request->uuid;
        }

        if ($request->get('show_all')){
            $this->validate($request, [
                'show_all' => 'required|boolean',
                'uuid' => 'required_if:show_all,false|uuid|missing'
            ]);
        }

        $listDocs = $this->workflowService->listDocs($uuid,$request->get('show_all'));
        return  $this->sendResponse($listDocs);
    }

    public function actionNewDocument(Request $request)
    {
        $this->validate($request, [
            'title' => 'required|min:3',
            'endDate' => 'required|date|after:startDate',
            'startDate' => 'nullable|date|after:now',
            'responsibles.*.id'=>'required|uuid|exists:Domain\Entities\Subscriber\Account,id',
        ]);

        $newDoc = $this->workflowService->_create($request->all());
        return  $this->sendResponse($newDoc);

    }

    public function actionDeleteDocument(Request $request) {
        $this->validate($request, [
            'uuid' => 'required|uuid|exists:Domain\Entities\Business\Document\Workflow,id',
        ]);
    }

    public function actionEditDocument(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:Domain\Entities\Business\Document\Workflow,id',
            'title' => 'required',
            'endDate' => 'required|date',
            'responsible'=>'required|array',
        ]);
    }


}
