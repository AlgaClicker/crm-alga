<?php

namespace Core\Http\Controllers\Crm;
use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Document\WorkflowServiceContract;

class WorkflowController extends Controller
{
    private WorkflowServiceContract $workflowService;

    public function __construct(WorkflowServiceContract $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function actionIndex(Request $request) {
        return  $this->sendResponse( $this->workflowService->listDocs());
    }

    public function actionListDocumentTask(Request $request) {
        $listTasks = [];
        $this->validate($request, [
            'id' => 'required|exists:Domain\Entities\Business\Document\Workflow,id',
        ]);

        return  $this->sendResponse($this->workflowService->listTasks($request->get('id')));
    }

    public function actionNewDocumentTask(Request $request) {

        $this->validate($request, [
            'workflow_uuid' => 'required|uuid|exists:Domain\Entities\Business\Document\Workflow,id',
            'title' => 'required:min:3',
            'endDate' => 'required|date|after:startDate',
            'startDate' => 'nullable|date|after:now',
            'accounts'=>'nullable|array',
            'files'=>'nullable|array',
        ]);
        $task = $this->workflowService->newTask($request->workflow_uuid,$request->all());
        return $this->sendResponse( $task );
    }
}
