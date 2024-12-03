<?php
use Illuminate\Support\Facades\Route;
Route::post('/requisitions/list',  ['as' => 'snabzheniyeRequisitionList', 'uses' => 'Crm\\SnabzheniyeController@requisitionListNoManager']);
Route::post('/requisitions/my',  ['as' => 'snabzheniyeRequisitionListMy', 'uses' => 'Crm\\SnabzheniyeController@requisitionListMy']);
Route::post('/requisitions/{requisitionId}/get',  ['as' => 'snabzheniyeRequisitionGetManager', 'uses' => 'Crm\\SnabzheniyeController@requisitionSetManager']);
Route::post('/requisitions/{requisitionId}/manage/clean',  ['as' => 'snabzheniyeRequisitionClearManager', 'uses' => 'Crm\\RequisitionController@requisitionCleanManager']);
Route::post('/requisitions/{requisitionId}/manage/set',  ['as' => 'snabzheniyeRequisitionSetManager', 'uses' => 'Crm\\RequisitionController@requisitionSetManager']);
Route::post('/invoices',  ['as' => 'snabzheniyeInvoicesList', 'uses' => 'Crm\\RequisitionController@InvoicesListManager']);


Route::post('/requisitions/{requisitionId}',  ['as' => 'snabzheniyeRequisitionInfoManager', 'uses' => 'Crm\\SnabzheniyeController@snabzheniyeRequisitionInfoManager']);
Route::get('/requisitions/{requisitionId}',  ['as' => 'snabzheniyeRequisitionInfoManager', 'uses' => 'Crm\\SnabzheniyeController@snabzheniyeRequisitionInfoManager']);

Route::get('/requisitions/{requisitionId}',  ['as' => 'snabzheniyeRequisitionInfoManager', 'uses' => 'Crm\\SnabzheniyeController@snabzheniyeRequisitionInfoManager']);

Route::post('/specifications',  ['as' => 'masterGetListMySpecifications', 'uses' => 'Crm\\MasterController@getListMySpecifications']);



Route::post('/requisition/{requisitionId}/material/',  ['as' => 'listMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@listMaterialRequisition']);
Route::post('/requisition/other/{requisitionId}/material/add',  ['as' => 'addMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@addMaterialRequisitionOther']);
Route::post('/requisition/{requisitionId}/material/{materialRequisitionId}/set/material',  ['as' => 'setMaterialRequisitionMaterialDirectoryOther', 'uses' => 'Crm\\RequisitionController@setMaterialRequisitionMaterialDirectoryOther']);
Route::post('/requisition/{requisitionId}/material/{materialRequisitionId}/unset/material',  ['as' => 'setMaterialRequisitionMaterialDirectoryOther', 'uses' => 'Crm\\RequisitionController@unSetMaterialRequisitionMaterialDirectoryOther']);

Route::post('/requisition/{requisitionId}/material/{materialRequisitionId}',  ['as' => 'editMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@showMaterialRequisition']);

Route::patch('/requisition/other/{requisitionId}/material/{materialRequisitionId}',  ['as' => 'editMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@editMaterialRequisitionOther']);
Route::delete('/requisition/other/{requisitionId}/material/{materialRequisitionId}',  ['as' => 'deleteMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@deleteMaterialRequisitionOther']);


Route::post('/requisitions/{requisitionId}/calculation',  ['as' => 'RequisitionCalculation', 'uses' => 'Crm\\RequisitionController@requisitionCalculation']);


Route::post('/requisitions/{requisitionId}/cancel',  ['as' => 'requisitionMaterialCancel', 'uses' => 'Crm\\RequisitionController@requisitionMaterialCancel']);
Route::post('/requisitions/{requisitionId}/work',  ['as' => 'snabzheniyeRequisitionWorkList', 'uses' => 'Crm\\RequisitionController@snabzheniyeRequisitionWork']);
Route::post('/requisitions/{requisitionId}/work/invoice/add',  ['as' => 'snabzheniyeRequisitionWorkList', 'uses' => 'Crm\\RequisitionController@snabzheniyeRequisitionWorkAdd']);

Route::post('/requisitions/{requisitionId}/work/invoices',  ['as' => 'snabzheniyeRequisitionWorkListInvoices', 'uses' => 'Crm\\RequisitionController@snabzheniyeRequisitionWorkListInvoices']);
Route::delete('/requisitions/{requisitionId}/work/invoice',  ['as' => 'deleteRequisitionInvoice', 'uses' => 'Crm\\RequisitionController@deleteRequisitionInvoice']);
Route::post('/requisitions/specification/{specificationId}/invoices/',  ['as' => 'specificationRequisitionInvoice', 'uses' => 'Crm\\RequisitionController@specificationRequisitionInvoice']);
