<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Domain\Contracts\Services\Crm\ObjectsServiceContracts;
use Domain\Contracts\Repository\Crm\ProjectsRepositoryContracts;

class ProjectsController extends Controller
{
    private ObjectsServiceContracts $objectsService;
    private ProjectsRepositoryContracts $projectsService;


    public function __construct(
        ObjectsServiceContracts $objectsService,
        ProjectsRepositoryContracts $projectsService
    ) {
        $this->objectsService = $objectsService;
        $this->projectsService = $projectsService;
    }

    public function actionNew(Request $request) {

        return  $this->sendResponse('actionNew');
    }

    public function actionShow($objectId, Request $request) {

    }

    public function actionList($objectId) {

        return  $this->sendResponse('actionList');
    }
    
    public function actionEdit($objectId,Request $request) {

    }

    public function actionDelete($objectId, Request $request) {

    }


}