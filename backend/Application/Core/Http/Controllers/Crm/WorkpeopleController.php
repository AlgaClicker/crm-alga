<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Domain\Contracts\Services\Crm\WorkpeopleServiceContract;
use Ramsey\Uuid\Uuid;


class WorkpeopleController extends Controller
{
    private WorkpeopleServiceContract $workpeopleService;

    public function __construct(WorkpeopleServiceContract $workpeopleService)
    {
        $this->workpeopleService = $workpeopleService;

    }

    public function actionList(Request $request)
    {

        if ($request->get('id')) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',

            ]);

            $workpeople = $this->workpeopleService->_getById($request->get('id'));
            return $this->sendResponse($workpeople);
        }
        $options = $request->get('options');

        $workpeople = $this->workpeopleService->getListWorkpeople( $options);
        return $this->sendResponse($workpeople['data'], $workpeople['options']);

    }

    public function actionGetIdWorkPeople($idWorkpeople,Request $request)
    {
        //$request->request->add(['id' => $idWorkpeople]);
        //$request->attributes->add(['id' => $idWorkpeople]);
        $request->query->add(['id' => $idWorkpeople]);
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',

        ]);
        $workpeople = $this->workpeopleService->_getById($request->get('id'));
        return $this->sendResponse($workpeople);
    }
    public function actionNew(Request $request)
    {
        $this->validate($request, [
            'surname' => 'required|string|min:2',
            'name' => 'required|string|min:2',
            'patronymic' => 'nullable|string|min:3',
            'brithAt' => 'nullable|date',
            'brith_at' => 'nullable|date',
            'inn' => 'nullable|integer|between:1000000000,999999999999',
            'snils' => 'nullable|string|min:10',
            'phoneNumber' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'phone_number' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'profession' => 'required|uuid|exists:Domain\Entities\Business\Directory\Profession,id',

            'account.id' => 'sometimes|uuid|exists:Domain\Entities\Subscriber\Account,id',
        ]);

        $workpeople = $this->workpeopleService->_create($request->all());

        return $this->sendResponse($workpeople);
    }

    public function actionDelete(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',

        ]);
        $workpeople = $this->workpeopleService->_delete($request->get('id'));
        return $this->sendResponse($workpeople);
    }


    public function actionEdit(Request $request)
    {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
            'surname' => 'required|string|min:2',
            'name' => 'required|string|min:2',
            'patronymic' => 'nullable|string|min:3',
            'brithAt' => 'nullable|date',
            'inn' => 'nullable|integer|between:1000000000,999999999999',
            'snils' => 'nullable|string|min:10',
            'phoneNumber' => 'nullable|regex:/^([0-9\s\-\+\(\)]*)$/|min:10',
            'profession' => 'required|uuid|exists:Domain\Entities\Business\Directory\Profession,id',
            'account.id' => 'sometimes|uuid|exists:Domain\Entities\Subscriber\Account,id',
        ]);
        $workpeople = $this->workpeopleService->_updateById($request->get('id'), $request->all());

        return $this->sendResponse($workpeople);

    }

    /*
     * 1-20 - Часов рабочих в сутках
     * б - Больничный
     * о - отпуск
     * ну - прогул по неувожительной причине
     * нп - прогул по увожительной причине
     * до - дополнительный отгул
     */
    public function actionGetAccountWorkPeople($idWorkPeople) {
        return $this->sendResponse($this->workpeopleService->getAccountWorkPeople($idWorkPeople));
    }


    public function actionTabelNew(Request $request) {

        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
            'date'=> 'required|date|date_format:yyyy-dd-mm',
            'time_amount' => 'required|string|in:0,1,2,3,4,5,6,7,8,9,10,11,12,б,о,ну,нп,до',
            'time_extra' => 'sometimes|numeric|min:0|max:16',
            'specificationId' => 'sometimes|required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'description'=>'sometimes|required|min:1'
        ]);

        $date = new \DateTimeImmutable($request->date('date'));
        $workPeople = $this->workpeopleService->setTimeSheet(
            $request->get('idWorkpeople'),
            $date,
            $request->get('time_amount'),
            $request->get('time_extra'),
            $request->get('specificationId'),
            $request->get('description')
        );
        return $this->sendResponse($workPeople);

    }

    public function actionTabelEdit(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Master\Timesheet,id',
            'time_amount' => 'required|string|in:1,2,3,4,5,6,7,8,9,10,11,12,б,о,ну,нп,б,до',
            'time_extra' => 'sometimes|numeric|min:0|max:16',
            'specificationId' => 'sometimes|required|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'description'=>'sometimes|required|min:1'
        ]);
        $date = new \DateTimeImmutable($request->date('date'));
        $timesheet = $this->workpeopleService->editTimeSheet(
            $request->get('id'),
            $request->get('time_amount'),
            $request->get('time_extra'),
            $request->get('specificationId'),
            $request->get('comment')
        );
        return $this->sendResponse($timesheet);
    }

    public function actionTabelList(Request $request) {
        $this->validate($request, [
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
            'month'=> 'required|numeric|min:1|max:12',
            'year'=> 'required|integer|between:2022,2100',
        ]);
        $workPeople = $this->workpeopleService->listTimeSheet($request->get('idWorkpeople'),$request->get('month'),$request->get('year'));
        return $this->sendResponse($workPeople);
    }

    public function actionSetMaster($idWorkpeople, Request $request) {
        $request->request->add(['idWorkpeople' => $idWorkpeople]);
        $this->validate($request, [
            'master' => 'sometimes|required|uuid|exists:Domain\Entities\Subscriber\Account,id',
            'idWorkpeople' => 'required|uuid|exists:Domain\Entities\Business\Company\Workpeople,id',
        ]);
        return $this->sendResponse($this->workpeopleService->setMaster($idWorkpeople,$request->get('master')));

    }


}
