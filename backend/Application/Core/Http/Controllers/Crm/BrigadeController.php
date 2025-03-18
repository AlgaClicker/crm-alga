<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Validator;
use Ramsey\Uuid\Uuid;
use Domain\Contracts\Services\Crm\BrigadeServiceContract;
use Domain\Contracts\Services\Crm\WorkpeopleServiceContract;


class BrigadeController extends Controller
{
    private BrigadeServiceContract $brigadeService;
    private WorkpeopleServiceContract $workpeopleService;

    public function __construct(BrigadeServiceContract $brigadeService,WorkpeopleServiceContract $workpeopleService)
    {
        $this->brigadeService = $brigadeService;
        $this->workpeopleService = $workpeopleService;
    }

    public function actionList(Request $request)
    {
        if ($request->get('id')) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Master\Brigade,id',

            ]);
            $brigade = $this->brigadeService->_getById($request->get('id'));
            return $this->sendResponse($brigade);
        }

        $brigade = $this->brigadeService->getAllBy([], $request->get('options'));
        return $this->sendResponse($brigade['data'], $brigade['options']);

    }

    public function actionNew(Request $request)
    {

        if (auth()->user()->getRoles()->getService() == "master") {
            $this->validate($request, [
                'name' => 'required|string|min:6',
            ]);
        } else {
            $this->validate($request, [
                'name' => 'required|string|min:6',
                'master' => 'required|uuid|exists:Domain\Entities\Subscriber\Account,id',
            ]);

        }

        $brigade = $this->brigadeService->create($request->all());
        return $this->sendResponse($brigade);

    }

    public function actionDelete(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Master\Brigade,id',
        ]);
        $brigade = $this->brigadeService->delete($request->get('id'));
        return $this->sendResponse($brigade);
    }

    public function actionEdit(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Master\Brigade,id',
            'master' => 'required|uuid|exists:Domain\Entities\Subscriber\Account,id',
            'name' => 'required|string|min:3',
        ]);
        $brigade = $this->brigadeService->_updateById($request->get('id'), $request->all());
        return $this->sendResponse($brigade);

    }

    public function actionListPeople(Request $request)
    {

        $this->validate($request, [
            'idBrigade' => 'required|uuid|exists:Domain\Entities\Business\Master\Brigade,id',
        ]);
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }

        $listPeopleBrigade = $this->brigadeService->listPeopleBrigade($request->get('idBrigade'),$request->all());
        return $this->sendResponse($listPeopleBrigade['data'], $listPeopleBrigade['options']);

    }

    public function setSpecificationsBrigade($idBrigade, Request $request) {
        $this->validate($request, [
            'specification_id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'date_end' => 'required|date|after:now',
        ]);
        $date_end = new \DateTimeImmutable($request->get('date_end'));
        return $this->sendResponse($this->brigadeService->setSpecificationsBrigade($idBrigade,$request->get('specification_id'),$date_end));
    }

    public function actionAddWorkpeopleBrigade($idBrigade, Request $request) {
        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
        ]);

        return $this->sendResponse($this->brigadeService->addPeopleBrigade($idBrigade,$request->get('idWorkpeople')));
    }

    public function actionMoveWorkpeopleBrigade($idBrigade, Request $request) {
        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
            'newBrigade' => 'required|uuid|exists:Domain\Entities\Business\Master\Brigade,id',
        ]);
        return $this->sendResponse($this->brigadeService->movePeopleBrigade($idBrigade,$request->get('idWorkpeople'),$request->get('newBrigade')));
    }



    public function actionRemoveWorkpeopleBrigade($idBrigade, Request $request) {
        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
        ]);
        return $this->sendResponse($this->brigadeService->removePeopleBrigade($idBrigade,$request->get('idWorkpeople')));
    }

    public function actionDeletePeople(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
        ]);
        $brigade = $this->brigadeService->deletePeople($request->get('id'));
        return $this->sendResponse($brigade);
    }

    public function actionNewPeople(Request $request)
    {
        $this->validate($request, [
            'idBrigade' => 'required|uuid|exists:Domain\Entities\Business\Master\Brigade,id',
            'surname' => 'required|string|min:2',
            'name' => 'required|string|min:2',
            'profession' => 'required|uuid|exists:Domain\Entities\Business\Directory\Profession,id',
            'patronymic' => 'nullable|string|min:3',
            'phoneNumber' => 'required|integer|digits:11'
        ]);
        $brigade = $this->brigadeService->createPeople(($request->all()));
        return $this->sendResponse($brigade);
    }

    public function actionEditPeople(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
            'surname' => 'required|string|min:2',
            'name' => 'required|string|min:2',
            'profession' => 'required|uuid|exists:Domain\Entities\Business\Directory\Profession,id',
            'patronymic' => 'nullable|string|min:3',
            'phoneNumber' => 'required|integer|digits:11',
            'idBrigade' => 'required|uuid|exists:Domain\Entities\Business\Master\Brigade,id',
        ]);
        $brigade = $this->brigadeService->updatePeople($request->all());
        return $this->sendResponse($brigade);
    }

    /*
     * 1-20 - Часов рабочих в сутках
     * б - Больничный
     * о - отпуск
     * ну - прогул по неувожительной причине
     * нп - прогул по увожительной причине
     * до - дополнительный отгул
     */
    public function actionTabelNew(Request $request) {
        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
            'date'=> 'required|date',
            'timeAmount' => 'required|string|in:1,2,3,4,5,6,7,8,9,10,11,12,б,о,ну,нп,б,до',
            'timeExtra' => 'sometimes|numeric|min:0|max:16',
            'specificationId' => 'sometimes|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'description'=>'sometimes|required|min:1'
        ]);
        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
        ]);

        $date = new \DateTimeImmutable($request->date('date'));
        $workPeople = $this->workpeopleService->setTimeSheet(
            $request->get('idWorkpeople'),
            $date,
            $request->get('timeAmount'),
            $request->get('timeExtra'),
            $request->get('specificationId'),
            $request->get('description')
        );
        return $this->sendResponse($workPeople);

    }

    public function actionTabelList(Request $request) {
        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
            'month'=> 'required|digits:2|between:1,12',
            'year'=> 'required|integer|between:2022,2100',
        ]);
        $workPeople = $this->brigadeService->listTimeSheet($request->get('idWorkpeople'),$request->get('month'),$request->get('year'));
        return $this->sendResponse($workPeople);
    }

    public function actionTabelEdit(Request $request) {
        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
            'date'=> 'required|date',
            'timeAmount' => 'required|string|in:1,2,3,4,5,6,7,8,9,10,11,12,б,о,ну,нп,б,до',
            'timeExtra' => 'sometimes|numeric|min:0|max:16',
            'specificationId' => 'sometimes|required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'description'=>'sometimes|required|min:1'
        ]);
        $date = new \DateTimeImmutable($request->date('date'));
        $workPeople = $this->workpeopleService->editTimeSheet(
            $request->get('idWorkpeople'),
            $date,
            $request->get('timeAmount'),
            $request->get('timeExtra'),
            $request->get('specificationId'),
            $request->get('description')
        );
        return $this->sendResponse($workPeople);
    }

    public function actionTabelDelete(Request $request) {
        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
            'date'=> 'required|date',
        ]);
        $date = new \DateTimeImmutable($request->date('date'));
        $workPeople = $this->workpeopleService->deleteTimeSheet($request->get('idWorkpeople'),$date);
        return $this->sendResponse($workPeople);

    }

    public function listSpecificationsBrigade($idBrigade,Request $request){
        if (!Uuid::isValid($idBrigade)) {
            return $this->sendResponse(null);
        }


        $workPeople = $this->brigadeService->listSpecificationsBrigade($idBrigade);
        return $this->sendResponse($workPeople);
    }

    public function deleteSpecificationsBrigade($idBrigade,Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Document\BrigadeSpecification,id',
        ]);
        return $this->sendResponse($this->brigadeService->deleteSpecificationsBrigade($idBrigade,$request->get('id')));
    }

    public function actionListWorkpeopleBrigade($idBrigade,Request $request) {
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }

        if (!Uuid::isValid($idBrigade)) {
            return $this->sendResponse(null);
        }
        $listPeopleBrigade = $this->brigadeService->listPeopleBrigade($idBrigade,$request->all());
        return $this->sendResponse($listPeopleBrigade['data'], $listPeopleBrigade['options']);
    }

    public function actionListPeopleFree(Request $request) {
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }
        $listPeopleBrigade = $this->brigadeService->listPeopleBrigadeFree($request->get('options'));
        return $this->sendResponse($listPeopleBrigade['data'], $listPeopleBrigade['options']);
    }

}
