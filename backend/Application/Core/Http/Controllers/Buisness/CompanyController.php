<?php

namespace Core\Http\Controllers\Buisness;

use Domain\Contracts\Services\Business\BusinessServiceContracts;
use Domain\Contracts\Services\AccountServiceContracts;
use Core\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CompanyController extends Controller
{
    private BusinessServiceContracts $buisnessService;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
        BusinessServiceContracts $buisnessService
    ){
        $this->buisnessService = $buisnessService;
    }

    public function showAccounts($idCompany) {
        $allAccounts = $this->buisnessService->listAccountsCompany($idCompany);
        return  $this->sendResponse($allAccounts);
    }

    public function showCompany(Request $request,$idCompany = null)
    {
        $options = [];
        $paginate = [];
        $orderParam = [];
        if ($request->isJson()) {
            $json = $request->json()->all();
            if (array_key_exists('options',$json)){
                if (array_key_exists('sort',$json['options'])){
                    $orderParam = $json['options']['sort'];
                }

                if (array_key_exists('paginate',$json['options'])){
                    $paginate = $json['options']['paginate'];
                    $count =  $this->buisnessService->companyCount();
                    $options['paginate']['count'] = $count;
                    $options['paginate']['page'] = $json['options']['paginate'];
                }
            }
        }

        if ($idCompany){
            $allCompany = $this->buisnessService->findAllCompanyBy(['id'=>$idCompany],array(),$paginate)[0];
        } else {
            $allCompany = $this->buisnessService->findAllCompanyBy(array(),$orderParam,$paginate);
        }
        return  $this->sendResponse($allCompany);
    }

    public function showMyCompnayStocks() {
        $allStockCompany = $this->buisnessService->showMyCompnayStocks();
        return  $this->sendResponse($allStockCompany);
    }

    public function addCompany(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'fullname' => 'required|string'
        ]);
       $newCompany =  $this->buisnessService->createCompany($request->all());
       return  $this->sendResponse($newCompany);
    }

    public function delCompany($idCompany) {
        $ondel = $this->buisnessService->removeCompany($idCompany);
        return  $this->sendResponse($ondel);
    }

    public function editCompany($idCompany,Request $request) {
        $this->validate($request, [
            'name' => 'required|string',
        ]);

        $editCompany = $this->buisnessService->updateCompnay($idCompany,$request->all());

        return  $this->sendResponse($editCompany);
    }

    public function showStock(Request $request)
    {
        if ($request->all() && array_key_exists('id',$request->all())) {
            $this->validate($request, [
                'id' => 'required|exists:Domain\Entities\Business\Company\Stock,id',
            ]);
        }

        if ($request->all() && array_key_exists('company_id',$request->all())) {
            $this->validate($request, [
                'company_id' => 'required|exists:Domain\Entities\Business\Company\Company,id',
            ]);
        }


        $allStockCompany= $this->buisnessService->getAllStockCompany($request->all());
        return  $this->sendResponse($allStockCompany);
    }

    public function editStock(Request $request) {

        $this->validate($request, [
            'id' => 'required|exists:Domain\Entities\Business\Company\Stock,id',
        ]);

        return  $this->sendResponse($this->buisnessService->editStockMyCompany($request->all()));


    }

    public function addStock(Request  $request)
    {

        if (auth()->user()->getRoles()->getService() != "administrator") {
            $this->validate($request, [
                'name' => 'required|string',
                'fullname' => 'required|string'
            ]);
        } else {
            $this->validate($request, [
                'company_id' => 'required|uuid|exists:Domain\Entities\Business\Company\Company,id',
                'name' => 'required|string',
                'fullname' => 'required|string'
            ]);
        }


        $addStockCompany= $this->buisnessService->addStockCompany($request->all());

        return  $this->sendResponse($addStockCompany);
    }

    public function deleteStock(Request $request) {
        $this->validate($request, [
            'id' => 'required|exists:Domain\Entities\Business\Company\Stock,id',
        ]);

        return  $this->sendResponse($this->buisnessService->removeStockMyCompany($request->get('id')));
    }

    //
}
