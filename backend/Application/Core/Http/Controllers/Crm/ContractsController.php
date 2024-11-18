<?php

namespace Core\Http\Controllers\Crm;

use Core\Http\Controllers\Controller;
use Domain\Contracts\Services\Document\ContractsServiceContract;
use Illuminate\Http\Request;

use Domain\Contracts\Services\AccountServiceContracts;
use Core\Exceptions\ApplicationException;
use Illuminate\Support\Facades\Storage;
use function Sodium\add;

class ContractsController extends Controller
{
    private AccountServiceContracts $accountService;
    private ContractsServiceContract $contractsService;


    public function __construct(AccountServiceContracts $accountService,ContractsServiceContract $serviceContract)
    {
        $this->accountService = $accountService;
        $this->contractsService = $serviceContract;
    }


    public function listContracts(Request $request) {

        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }

        $listContracts = $this->contractsService->getListContracts($request->get('options'));
        return  $this->sendResponse($listContracts);
    }

    public function listWorkContracts(Request $request) {
        $options['options']['filter']['type']='construction';
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
            $options = $request->get('options');


            //$options['filter'] = ['type'=>'construction'];
            //dd($options);
        }

        $listContracts = $this->contractsService->getListWorkContracts($options);
        return  $this->sendResponse($listContracts['data'],$listContracts['options']);
    }
    public function listSupplyContracts(Request $request) {
        $options['options']['filter']['type']='supply';
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
            $options = $request->get('options');
        }

        $listContracts = $this->contractsService->getListSupplyContracts($options);
        return  $this->sendResponse($listContracts['data'],$listContracts['options']);
    }

    public function workContractNew(Request $request) {

        $this->validate($request, [
            'specifications.*.id' => 'nullable|sometimes|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'partner.id' => 'required|sometimes|uuid|exists:Domain\Entities\Business\Directory\Partner,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'number' => 'required|string',
            'description' => 'sometimes|required|string',
            'amount' => 'required|numeric|min:0',
            'credit_amount' => 'sometimes|required|numeric|min:0',
            'credit_at_days' => 'required_with:credit_amount|numeric|min:1|max:3650',
            'credit' => 'sometimes|required|numeric|min:0',
            'credit_days' => 'required_with:credit|null|digits|min:0|max:3650',

            'tax' =>'required|numeric|min:0|max:100',
        ]);

        return  $this->sendResponse($this->contractsService->addСonstructionContract($request->all()));
    }

    public function addSupplyContractNew(Request $request) {

        $this->validate($request, [
            'specifications.*.id' => 'nullable|sometimes|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'partner.id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Partner,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'number' => 'required|string',
            'description' => 'nullable|sometimes|required|string',
            'amount' => 'nullable|numeric|min:0',
            'credit' => 'nullable|numeric|min:0',
            'credit_days' => 'required_with:credit|digits_between:0,3650',
            'tax' =>'required|numeric|min:0|max:100',
        ]);

        return  $this->sendResponse($this->contractsService->addSupplyContract($request->all()));
    }

    public function fixContractId($idContract, Request $request){

        $request->request->add(['contract_id' => $idContract]);
        $this->validate($request, [
            'contract_id' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id'
        ]);
        return  $this->sendResponse($this->contractsService->_setFixed($request->get('contract_id'),true));
    }

    public function unFixContractId($idContract, Request $request){
        $request->request->add(['contract_id' => $idContract]);
        $this->validate($request, [
            'contract_id' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id'
        ]);
        return  $this->sendResponse($this->contractsService->_setFixed($request->get('contract_id'),false));
    }

    public function deleteContractId($idContract, Request $request)
    {
        $request->request->add(['contract_id' => $idContract]);
        $this->validate($request, [
            'contract_id' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id'
        ]);
        return  $this->sendResponse($this->contractsService->remove($request->get('contract_id')));
    }

    public function editContractId($idContract, Request $request)
    {

        $request->request->add(['contract_id' => $idContract]);
        $this->validate($request, [
            'contract_id' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id',
            'specification.*.id' => 'sometimes|uuid|exists:Domain\Entities\Business\Objects\Specification,id',
            'partner.id' => 'required|uuid|exists:Domain\Entities\Business\Directory\Partner,id',
            'start_at' => 'required|date',
            'end_at' => 'required|date|after:start_at',
            'number' => 'required|string',
            'description' => 'sometimes|required|string',
            'amount' => 'required|numeric|min:0',
            'credit_amount' => 'sometimes|required|numeric|min:0',
            'credit_at_days' => 'required_with:credit_amount|digits|min:0|max:3650',
            'tax' =>'required|numeric|min:0|max:100',
            'tax_amount' =>'sometimes|required_with:tax|float|min:0.01',
        ]);

        return $this->sendResponse($this->contractsService->_updateById($idContract,$request->all()));
    }

    public function addSpecificationContract($contractId,$specificationId,Request $request)
    {
        if (count($request->all()) == 0) {
            $request->request->add(['contract_id' => $contractId]);
            $request->request->add(['specification_id' => $specificationId]);
            $this->validate($request, [
                'contract_id' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id',
                'specification_id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id'

            ]);
            return $this->sendResponse($this->contractsService->addSpecificationContract($contractId,$specificationId));
        }
        abort(415,'Ошибка. Запрос не пустой');
    }
    public function removeSpecificationContract($contractId,$specificationId,Request $request)
    {
        if (count($request->all()) == 0) {
            $request->request->add(['contract_id' => $contractId]);
            $request->request->add(['specification_id' => $specificationId]);
            $this->validate($request, [
                'contract_id' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id',
                'specification_id' => 'required|uuid|exists:Domain\Entities\Business\Objects\Specification,id'

            ]);
            return $this->sendResponse($this->contractsService->removeSpecificationContract($contractId,$specificationId));
        }
        abort(415,'Ошибка. Запрос не пустой');
    }

    public function listSpecificationContract($contractId,Request $request)
    {
        $request->request->add(['contract_id' => $contractId]);
        $this->validate($request, [
            'contract_id' => 'required|uuid|exists:Domain\Entities\Business\Document\Contracts,id',
        ]);
        if (array_key_exists('options', $request->all())) {
            $this->validate($request, [
                'options.pagginate.limit' => 'sometimes|required|in:5,10,25,50,100',
                'options.pagginate.page' => 'required_with:options.pagginate.limit|required|int|min:1',
                'options.orderBy.*' => 'sometimes|required|string|in:ASC,DESC',
            ]);
        }
        return $this->sendResponse($this->contractsService->listSpecificationContract($contractId,$request->get('options')));

    }
}
