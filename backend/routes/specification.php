<?php
use Illuminate\Support\Facades\Route;


Route::group([
    'prefix' => '/specification',
    'middleware' => ['jwt','cors','throttle:1600,3'],
], function ($router) {
    Route::post('/',  ['as' => 'specificationList', 'uses' => 'Crm\SpecificationController@showSpec','help'=>[
        "id"=>"Идентификатор спецификации, выводит спецификацю",
        "objectId"=>"Идентификатор объекта, выводит массив спецификаций"
    ]]);
    Route::post('/ready',  ['as' => 'specificationListReady', 'uses' => 'Crm\SpecificationController@showSpecReadyNoFix']);
    Route::post('/history',  ['as' => 'specificationList', 'uses' => 'Crm\SpecificationController@showSpecHistory']);
    Route::post('/add',  ['as' => 'specificationNew', 'uses' => 'Crm\SpecificationController@newSpec']);
    Route::patch('/edit',  ['as' => 'specificationEdit', 'uses' => 'Crm\SpecificationController@editSpec']);
    Route::patch('/',  ['as' => 'specificationEdit', 'uses' => 'Crm\SpecificationController@editSpec']);
    Route::delete('/',  ['as' => 'specificationDelete', 'uses' => 'Crm\SpecificationController@deleteSpec']);

    Route::post('/import/file',  ['as' => 'specificationImportFile', 'uses' => 'Crm\SpecificationController@loadNewSpec']);
    Route::post('/fixed',  ['as' => 'specificationFixed', 'uses' => 'Crm\SpecificationController@fixSpec']);
    Route::post('/unfixed',  ['as' => 'specificationFixed', 'uses' => 'Crm\SpecificationController@unFixSpec']);
    Route::post('/change/add',  ['as' => 'specificationChangeNew', 'uses' => 'Crm\SpecificationController@specificationChangeNew']);
    Route::post('/change/',  ['as' => 'specificationChangeList', 'uses' => 'Crm\SpecificationController@specificationChangeList']);
    Route::post('/change/{idx:[0-9]}',  ['as' => 'specificationChangeView', 'uses' => 'Crm\SpecificationController@specificationChangeView']);
    Route::post('/all',  ['as' => 'specificationChangeView', 'uses' => 'Crm\SpecificationController@specificationAll']);

    Route::post('/{specificationId}/material',  ['as' => 'specificationMaterial', 'uses' => 'Crm\SpecificationController@showSpecIdMaterial']);
    Route::post('/{specificationId}/calculation',  ['as' => 'specificationCalculation', 'uses' => 'Crm\SpecificationController@specificationCalculation']);
    Route::post('/{specificationId}/material/remnants',  ['as' => 'specificationMaterialRemnants', 'uses' => 'Crm\SpecificationController@specificationMaterialRemnants']);

    Route::post('/{specificationId}/material/add',  ['as' => 'specificationMaterial', 'uses' => 'Crm\SpecificationController@showSpecIdMaterialNew']);
    Route::patch('/{specificationId}/material',  ['as' => 'specificationMaterialEdit', 'uses' => 'Crm\SpecificationController@actionSpecIdMaterialEdit']);
    Route::delete('/{specificationId}/material',  ['as' => 'specificationMaterialDelete', 'uses' => 'Crm\SpecificationController@showSpecIdMaterialDelete']);
    Route::post('/{specificationId}/material/{idMaterial}',  ['as' => 'specificationMaterial', 'uses' => 'Crm\SpecificationController@showSpecIdMaterialId']);


    Route::post('/material',  ['as' => 'specificationMaterial', 'uses' => 'Crm\SpecificationController@showSpecMaterial']);
    Route::post('/material/add',  ['as' => 'specificationMaterial', 'uses' => 'Crm\SpecificationController@showSpecMaterialNew']);
    Route::patch('/material',  ['as' => 'specificationMaterialEdit', 'uses' => 'Crm\SpecificationController@actionSpecMaterialEdit']);
    Route::delete('/material',  ['as' => 'specificationMaterialDelete', 'uses' => 'Crm\SpecificationController@showSpecMaterialDelete']);
    Route::post('/material/history',  ['as' => 'specificationMaterialHistory', 'uses' => 'Crm\SpecificationController@showSpecMaterialHistory']);
    Route::post('/material/search',  ['as' => 'specificationMaterialSearch', 'uses' => 'Crm\SpecificationController@specificationMaterialSearch']);

    Route::post('/typeworks',  ['as' => 'specificationTypeWorks', 'uses' => 'Crm\SpecificationController@showSpecTypeWork']);
    Route::post('/typeworks/add',  ['as' => 'specificationTypeWorksNew', 'uses' => 'Crm\SpecificationController@showSpecTypeWorkNew']);
    Route::patch('/typeworks',  ['as' => 'specificationTypeWorksEdit', 'uses' => 'Crm\SpecificationController@showSpecTypeWorkEdit']);
    Route::delete('/typeworks',  ['as' => 'specificationTypeWorksDelete', 'uses' => 'Crm\SpecificationController@showSpecTypeWorkDelete']);

    Route::post('/resources',  ['as' => 'specificationResources', 'uses' => 'Crm\SpecificationController@showSpecResources']);
    Route::post('/resources/add',  ['as' => 'specificationResourcesNew', 'uses' => 'Crm\SpecificationController@showSpecResourcesNew']);
    Route::patch('/resources',  ['as' => 'specificationResourcesEdit', 'uses' => 'Crm\SpecificationController@showSpecResourcesEdit']);
    Route::delete('/resources',  ['as' => 'specificationResourcesDelete', 'uses' => 'Crm\SpecificationController@showSpecResourcesDelete']);

    Route::post('/{specificationId}/responsible',  ['as' => 'specificationResponsible', 'uses' => 'Crm\SpecificationController@specificationResponsible']);
    Route::post('/{specificationId}/responsible/add',  ['as' => 'specificationResponsibleAdd', 'uses' => 'Crm\SpecificationController@specificationResponsibleAdd']);
    Route::delete('/{specificationId}/responsible',  ['as' => 'specificationResponsibleDelete', 'uses' => 'Crm\SpecificationController@specificationResponsibleDelete']);


    Route::post('/{specificationId}/contract',  ['as' => 'specificationGetContract', 'uses' => 'Crm\SpecificationController@specificationGetContract']);
    Route::post('/{specificationId}/contract/set',  ['as' => 'specificationSetContract', 'uses' => 'Crm\SpecificationController@specificationSetContract']);
    Route::post('/{specificationId}/contract/{contractId}/set',  ['as' => 'specificationSetContract', 'uses' => 'Crm\SpecificationController@specificationSetContractId']);
    Route::post('/{specificationId}/contract/clean',  ['as' => 'specificationCleanContract', 'uses' => 'Crm\SpecificationController@specificationCleanContract']);
});






