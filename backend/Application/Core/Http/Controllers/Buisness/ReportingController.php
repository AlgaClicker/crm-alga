<?php

namespace Core\Http\Controllers\Buisness;
use Core\Http\Controllers\Controller;
use Domain\Contracts\Repository\Crm\BrigadeRepositoryContracts;
use Domain\Contracts\Services\Business\ReportingServiceContracts;
use Illuminate\Http\Request;




class ReportingController extends Controller
{
    private ReportingServiceContracts $reportingService;
    protected BrigadeRepositoryContracts $brigadeRepository;
    public function __construct( ReportingServiceContracts $reportingService)
    {

        $this->reportingService = $reportingService;
    }

    public function actionIncome(Request $request) {
        $report = $this->reportingService->income($request->get('options'));
        return  $this->sendResponse($report['data'],$report['options']);
    }

    public function reportBrigades(Request $request) {

        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }

        $this->validate($request, [
            'idBrigade' => 'nullable|uuid|exists:Domain\Entities\Business\Master\Brigade,id',
            'partner' => 'nullable|uuid|exists:Domain\Entities\Business\Directory\Partner,id',
            'startDate' => 'required|date|date_format:d-m-Y',
            'endDate' => 'required|date|date_format:d-m-Y',
            'specification' => 'nullable|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);
        $report = $this->reportingService->repostBrigades($request->all());
        return  $this->sendResponse($report['data'],$report['options']);
    }

    public function reportSpecification(Request $request) {
        $this->validate($request, [
            'startDate' => 'required|date',
            'endDate' => 'required|date',
            'specificationId' => 'nullable|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'specifications.*.id' => 'nullable|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);
        $report = $this->reportingService->reportSpecification($request->all());
        return  $this->sendResponse($report);
    }

    public function reportAccountsActive(Request $request) {
        $this->validate($request, [
            'id' => 'nullable|uuid|exists:Domain\Entities\Subscriber\Account,id',
            'accounts.*.id' => 'nullable|uuid|exists:Domain\Entities\Subscriber\Account,id',
        ]);

        $report = $this->reportingService->accountsActive($request->json()->all());
        return  $this->sendResponse($report['data'],$report['options']);
    }
}
