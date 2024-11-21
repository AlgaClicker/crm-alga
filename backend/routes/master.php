<?php

use Illuminate\Support\Facades\Route;

//Route::post('/',  ['as' => 'listDocument', 'uses' => 'DocumentsController@actionListDocument']);
Route::post('/specifications',  ['as' => 'masterGetListMySpecifications', 'uses' => 'Crm\\MasterController@getListMySpecifications']);
Route::post('/specification/{specificationId}/materials',  ['as' => 'masterGetListMySpecifications', 'uses' => 'Crm\\MasterController@getListMaterialsSpecification']);

Route::post('/requisition/other/new',  ['as' => 'listRequisitionOther', 'uses' => 'Crm\\MasterController@newRequisitionOther']);
Route::post('/requisition/other',  ['as' => 'listRequisitionOther', 'uses' => 'Crm\\RequisitionController@listRequisitionOther']);
Route::patch('/requisition/other',  ['as' => 'editRequisitionOther', 'uses' => 'Crm\\RequisitionController@editRequisitionOther']);
Route::delete('/requisition/other',  ['as' => 'deleteRequisitionOther', 'uses' => 'Crm\\RequisitionController@deleteRequisitionOther']);
Route::post('/requisition/other/{requisitionId}',  ['as' => 'showByIdRequisitionOther', 'uses' => 'Crm\\MasterController@masterRequisitionShowById']);

Route::post('/requisition/other/{requisitionId}/fixed',  ['as' => 'fixedRequisitionOther', 'uses' => 'Crm\\MasterController@fixedRequisitionOther']);
Route::patch('/requisition/other/{requisitionId}/fixed',  ['as' => 'fixedRequisitionOther', 'uses' => 'Crm\\MasterController@fixedRequisitionOther']);

Route::post('/requisition/other/{requisitionId}/unfixed',  ['as' => 'unfixedRequisitionOther', 'uses' => 'Crm\\MasterController@unfixedRequisitionOther']);
Route::patch('/requisition/other/{requisitionId}/unfixed',  ['as' => 'unfixedRequisitionOther', 'uses' => 'Crm\\MasterController@unfixedRequisitionOther']);


Route::post('/requisition/{requisitionId}/material/',  ['as' => 'listMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@listMaterialRequisition']);
Route::post('/requisition/{requisitionId}/material/add',  ['as' => 'addMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@addMaterialRequisitionSpecification']);
Route::patch('/requisition/{requisitionId}/material/{materialRequisitionId}',  ['as' => 'editMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@editMaterialRequisitionOther']);
Route::delete('/requisition/{requisitionId}/material/{materialRequisitionId}',  ['as' => 'deleteMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@deleteMaterialRequisitionOther']);

Route::post('/requisition/other/{requisitionId}/material/',  ['as' => 'listMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@listMaterialRequisition']);
Route::post('/requisition/other/{requisitionId}/material/add',  ['as' => 'addMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@addMaterialRequisitionOther']);
Route::patch('/requisition/other/{requisitionId}/material/{materialRequisitionId}',  ['as' => 'editMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@editMaterialRequisitionOther']);
Route::delete('/requisition/other/{requisitionId}/material/{materialRequisitionId}',  ['as' => 'deleteMaterialRequisitionOther', 'uses' => 'Crm\\RequisitionController@deleteMaterialRequisitionOther']);

$uuid = '[a-f0-9]{8}-?[a-f0-9]{4}-?4[a-f0-9]{3}-?[89ab][a-f0-9]{3}-?[a-f0-9]{12}';

Route::post('/requisition',  ['as' => 'listRequisitionSpecificationMaterials', 'uses' => 'Crm\\MasterController@listRequisition']);
Route::patch('/requisition',  ['as' => 'listRequisitionMaterials', 'uses' => 'Crm\\MasterController@editRequisitionSpecificationMaterials']);
Route::delete('/requisition',  ['as' => 'deleteRequisitionMaster', 'uses' => 'Crm\\MasterController@deleteRequisitionSpecificationMaster']);
Route::post('/specification/{specificationId}/requisition/material',  ['as' => 'masterNewRequisitionSpecificationMaterials', 'uses' => 'Crm\\MasterController@newRequisitionSpecificationMaterials']);

Route::post('/requisition/{requisitionId}',  ['as' => 'masterRequisitionShowById', 'uses' => 'Crm\\MasterController@masterRequisitionShowById']);
Route::get('/requisition/{requisitionId}',  ['as' => 'masterRequisitionShowByIdGet', 'uses' => 'Crm\\MasterController@masterRequisitionShowById']);

//Route::pattern('requisitionId', $uuid);
Route::post('/requisition/{requisitionId}/fixed',  ['as' => 'masterRequisitionFixed', 'uses' => 'Crm\\MasterController@newRequisitionFixed']);
Route::post('/requisition/{requisitionId}/unfixed',  ['as' => 'masterRequisitionUnFixed', 'uses' => 'Crm\\MasterController@newRequisitionUnFixed']);

Route::patch('/requisition/{requisitionId}/fixed',  ['as' => 'masterRequisitionFixed', 'uses' => 'Crm\\MasterController@newRequisitionFixed']);
Route::patch('/requisition/{requisitionId}/unfixed',  ['as' => 'masterRequisitionUnFixed', 'uses' => 'Crm\\MasterController@newRequisitionUnFixed']);


Route::post('/specification/requisition/material',  ['as' => 'masterNewRequisitionMaterials', 'uses' => 'Crm\\MasterController@newRequisitionMaterials']);

Route::get('/requisition/{requisitionId}/delivery/{deliveryId}/confirmed',  ['as' => 'deliveryMasterMaterialsСonfirmedList', 'uses' => 'Crm\\RequisitionController@deliveryMasterMaterialsСonfirmedList']);

Route::post('/requisition/{requisitionId}/delivery',  ['as' => 'deliveryRequisition', 'uses' => 'Crm\\RequisitionController@deliveryMaterials']);
Route::post('/requisition/{requisitionId}/delivery/{deliveryId}',  ['as' => 'deliveryRequisition', 'uses' => 'Crm\\RequisitionController@deliveryMaterials']);
Route::post('/requisition/{requisitionId}/deliverys/',  ['as' => 'deliveryRequisition', 'uses' => 'Crm\\RequisitionController@deliveryMasterListRequisition']);
Route::post('/requisition/{requisitionId}/delivery/{deliveryId}/confirmed',  ['as' => 'deliveryRequisitionСonfirmed', 'uses' => 'Crm\\RequisitionController@deliveryMasterMaterialsСonfirmed']);
Route::post('/requisition/{requisitionId}/delivery/{deliveryId}/progress',  ['as' => 'deliveryRequisitionProgress', 'uses' => 'Crm\\RequisitionController@deliveryRequisitionProgress']);
