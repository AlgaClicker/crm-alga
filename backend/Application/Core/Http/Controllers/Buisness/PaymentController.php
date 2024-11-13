<?php

namespace Core\Http\Controllers\Buisness;


use Domain\Contracts\Services\Business\PaymentsServiceContract;
use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    private PaymentsServiceContract $paymentsService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        PaymentsServiceContract $paymentsService,
        Request $request
    ){
        $this->paymentsService = $paymentsService;
        parent::__construct($paymentsService,$request);
    }

    public function invoiceList(Request $request) {

        if (array_key_exists('id', $request->all())) {

            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Payments\Invoice,id',
            ]);
            return $this->sendResponse($this->paymentsService->invoiceOnly($request->get('id')));
        }

        if (gettype($request->get('options')) === 'array') {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
                'options.filter.date.start' => 'nullable|date',
                'options.filter.date.end' => 'nullable|required_with:options.filter.date.start|date',
                'options.filter.amount.start' => 'nullable|numeric|min:0',
                'options.filter.amount.end' => 'nullable|required_with:options.filter.amount.start|numeric',

            ]);
        }
        $list = $this->paymentsService->invoiceList($request->get('options'));
        return $this->sendResponse($list['data'], $list['options']);

    }

    public function invoiceImport(Request $request) {
        $this->validate($request, [
            'type' => 'required|in:sber,tochka,1c',
        ]);
        return  $this->sendResponse($this->paymentsService->invoiceImport($request->file('file'),$request->get('type')));
    }

    public function invoiceCreate(Request $request) {
        $this->validate($request, [
            'partner' => 'required|uuid|exists:Domain\Entities\Business\Directory\Partner,id',
            'type' => 'required|in:in,out,free',
            'date' => 'required|date',
            'description' => 'required|string',
            'specification' => 'nullable|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);
        return $this->sendResponse($this->paymentsService->invoiceCreate($request->all()));
    }

    public function invoiceDelete(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Payments\Invoice,id',
        ]);
        return $this->sendResponse($this->paymentsService->invoiceDelete($request->get('id')));
    }

    public function invoiceUpdate(Request $request) {
        $this->validate($request, [
            'id' => 'required|uuid|exists:Domain\Entities\Business\Payments\Invoice,id',
            'type' => 'required|in:in,out,free',
            'date' => 'required|date',
            'description' => 'required|string',
            'specification' => 'nullable|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
        ]);

        return $this->sendResponse($this->paymentsService->invoiceUpdate($request->all()));
    }


    public function brigadeList(Request $request) {
        if ($request->get('id')) {
            $this->validate($request, [
                'id' => 'required|uuid|exists:Domain\Entities\Business\Master\Brigade,id',
            ]);
        }

        if ($request->get('options') && is_array($request->get('options'))) {
            if (array_key_exists('orderBy',$request->get('options'))) {
                $this->validate($request, [
                    'options.orderBy.*' => 'nullable|in:ASC,DESC',
                ]);
            }

            if (array_key_exists('filter',$request->get('options'))) {
                $this->validate($request, [
                    'options.filter.date.*' => 'nullable|date',
                ]);
            }

            if (array_key_exists('pagginate',$request->get('options'))) {
                $this->validate($request, [
                    'options.pagginate.page' => 'nullable|numeric|min:1',
                    'options.pagginate.limit' => 'nullable|in:5,10,25,50,100',
                ]);
            }
        }

        $brigadeList = $this->paymentsService->brigadePayList($request->get('id'),$request->get('options'));
        return $this->sendResponse($brigadeList['data'], $brigadeList['options']);
    }

    public function brigadeCreate(Request $request) {
        $this->validate($request, [
            'brigade' => 'required|uuid|exists:Domain\Entities\Business\Master\Brigade,id',
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'type' => 'required|in:advance,salary,other',
        ]);
        return $this->sendResponse( $this->paymentsService->brigadePayCreate($request->all()));
    }

    public function brigadeUpdate(Request $request) {

    }

    public function brigadeDelete(Request $request) {

    }

}
