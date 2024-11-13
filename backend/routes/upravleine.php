<?php
use Illuminate\Support\Facades\Route;


Route::post('/requisitions/{requisitionId}',  ['as' => 'upravlenieRequisitionInfoManager', 'uses' => 'Crm\\SnabzheniyeController@snabzheniyeRequisitionInfoManager']);
Route::get('/requisitions/{requisitionId}',  ['as' => 'upravlenieRequisitionInfoManagerGet', 'uses' => 'Crm\\SnabzheniyeController@snabzheniyeRequisitionInfoManager']);
Route::post('/requisitions',  ['as' => 'upravlenieRequisitionList', 'uses' => 'Crm\\RequisitionController@listRequisition']);
Route::post('/requisition/{requisitionId}/material',  ['as' => 'listMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@listMaterialRequisition']);


Route::post('/contracts',  ['as' => 'workContractNew', 'uses' => 'Crm\\ContractsController@listContracts']);
Route::patch('/contract/{idContract}',  ['as' => 'workContractNew', 'uses' => 'Crm\\ContractsController@editContractId']);
Route::post('/contracts/work/add',  ['as' => 'workContractNew', 'uses' => 'Crm\\ContractsController@workContractNew']);
Route::post('/contracts/supply/add',  ['as' => 'supplyContractNew', 'uses' => 'Crm\\ContractsController@addSupplyContractNew']);

Route::post('/contract/{idContract}/fix',  ['as' => 'supplyContractFix', 'uses' => 'Crm\\ContractsController@fixContractId']);
Route::post('/contract/{idContract}/unfix',  ['as' => 'supplyContractUnFix', 'uses' => 'Crm\\ContractsController@unFixContractId']);

/*
Route::post('/requisitions/{requisitionId}',  ['as' => 'snabzheniyeRequisitionInfoManager', 'uses' => 'Crm\\SnabzheniyeController@snabzheniyeRequisitionInfoManager']);
Route::get('/requisitions/{requisitionId}',  ['as' => 'snabzheniyeRequisitionInfoManager', 'uses' => 'Crm\\SnabzheniyeController@snabzheniyeRequisitionInfoManager']);


//Route::post('/requisitions/{requisitionId}/setstatus',  ['as' => 'requisitionMaterialCancel', 'uses' => 'Crm\\RequisitionController@requisitionMaterialCancel']);
Route::post('/requisitions/list',  ['as' => 'snabzheniyeRequisitionList', 'uses' => 'Crm\\SnabzheniyeController@requisitionList']);



Route::post('/requisition/other/{requisitionId}/material/add',  ['as' => 'addMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@addMaterialRequisitionOther']);
Route::post('/requisition/{requisitionId}/material/{materialRequisitionId}/set/material',  ['as' => 'setMaterialRequisitionMaterialDirectoryOther', 'uses' => 'Crm\\RequisitionController@setMaterialRequisitionMaterialDirectoryOther']);
Route::post('/requisition/{requisitionId}/material/{materialRequisitionId}/unset/material',  ['as' => 'setMaterialRequisitionMaterialDirectoryOther', 'uses' => 'Crm\\RequisitionController@unSetMaterialRequisitionMaterialDirectoryOther']);

Route::post('/requisition/{requisitionId}/material/{materialRequisitionId}',  ['as' => 'editMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@showMaterialRequisition']);
*/
