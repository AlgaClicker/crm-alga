<?php
namespace routes;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => '/payments',
    'middleware' => ['jwt','cors','throttle:800,1'],
], function ($router) {
    Route::post('invoice',  ['as' => 'invoiceList', 'uses' => 'Buisness\PaymentController@invoiceList']);
    Route::post('invoice/import',  ['as' => 'invoiceList', 'uses' => 'Buisness\PaymentController@invoiceImport']);
    Route::post('invoice/add',  ['as' => 'invoiceCreate', 'uses' => 'Buisness\PaymentController@invoiceCreate']);
    Route::patch('invoice',  ['as' => 'invoiceUpdate', 'uses' => 'Buisness\PaymentController@invoiceUpdate']);
    Route::delete('invoice',  ['as' => 'invoiceDelete', 'uses' => 'Buisness\PaymentController@invoiceDelete']);
    Route::post('brigade',  ['as' => 'invoiceList', 'uses' => 'Buisness\PaymentController@brigadeList']);
    Route::post('brigade/add',  ['as' => 'invoiceList', 'uses' => 'Buisness\PaymentController@brigadeCreate']);
    Route::patch('brigade',  ['as' => 'invoiceList', 'uses' => 'Buisness\PaymentController@brigadeUpdate']);
    Route::delete('brigade',  ['as' => 'invoiceList', 'uses' => 'Buisness\PaymentController@brigadeDelete']);
});

Route::get('objects',  ['as' => 'objectsList', 'uses' => 'Crm\ObjectsController@actionList']);
Route::post('objects',  ['as' => 'objectsList', 'uses' => 'Crm\ObjectsController@actionList']);
Route::post('objects/add',  ['as' => 'objectsNew', 'uses' => 'Crm\ObjectsController@actionNew']);
Route::get('objects/{objectId}',  ['as' => 'objectsShowOnly', 'uses' => 'Crm\ObjectsController@actionShow']);
Route::get('object/{objectId}',  ['as' => 'objectsShowOnly', 'uses' => 'Crm\ObjectsController@actionShow']);
Route::patch('objects',  ['as' => 'objectsEditOnly', 'uses' => 'Crm\ObjectsController@actionEdit']);
Route::delete('objects',  ['as' => 'objectDeleteOnly', 'uses' => 'Crm\ObjectsController@actionDelete']);


Route::post('objects/{objectId}/specification/import/',  ['as' => 'specificationLoadFile', 'uses' => 'Crm\SpecificationController@loadSpecFile']);

Route::post('object/{objectId}/specifications/',  ['as' => 'objectsShowOnly', 'uses' => 'Crm\SpecificationController@showSpecificationsByObject']);

/*
Route::post('objects/{objectId}/projects',  ['as' => 'projectsList', 'uses' => 'Crm\ProjectsController@actionList']);
Route::post('objects/{objectId}/projects/add',  ['as' => 'projectsNew', 'uses' => 'Crm\ProjectsController@actionNew']);
Route::post('objects/{objectId}/projects/{projectId:[0-9]+}',  ['as' => 'projectsShowOnly', 'uses' => 'Crm\ProjectsController@actionList']);
Route::patch('objects/{objectId}/projects/{projectId:[0-9]+}',  ['as' => 'projectsEditOnly', 'uses' => 'Crm\ProjectsController@actionEdit']);
Route::delete('objects/{objectId}/projects/',  ['as' => 'projectsDelete', 'uses' => 'Crm\ProjectsController@actionDelete']);
*/

Route::post('/file/{hash}/info',  ['as' => 'fileGetInfo', 'uses' => 'ServicesController@actionFileInfo']);
Route::post('/file/info',  ['as' => 'fileInfo', 'uses' => 'ServicesController@actionFileInfo']);
Route::post('/file/public',  ['as' => 'filePublic', 'uses' => 'ServicesController@actionFilePublic']);

Route::get('/file/{hash}',  ['as' => 'fileGet', 'uses' => 'ServicesController@actionFileGet']);
Route::post('/file/',  ['as' => 'fileGetJson', 'uses' => 'ServicesController@actionFileGetJson']);

Route::post('/file/upload',  ['as' => 'fileLoad', 'uses' => 'ServicesController@actionFileLoad']);


Route::get('/account/help',  ['as' => 'listAccountCompany', 'uses' => 'Crm\AccountsController@actionHelpAccount']);
Route::post('/account',  ['as' => 'listAccountCompany', 'uses' => 'Crm\AccountsController@actionList']);
Route::post('/account/add', ['as' => 'accountsCrm', 'uses' => 'Buisness\AccountsController@actionNewAccount']);
Route::patch('/account', ['as' => 'accountsEditCrm', 'uses' => 'Crm\AccountsController@actionEditAccount']);

Route::post('/account/roles',  ['as' => 'crmAccountRoles', 'uses' => 'Crm\AccountsController@actionListAccountRoles']);
Route::post('/account/role',  ['as' => 'crmAccountRoles', 'uses' => 'Crm\AccountsController@actionListRoles']);
Route::post('/account/option', ['as' => 'crmAccountOtions', 'uses' => 'Buisness\AccountsController@actionOption']);
Route::patch('/account/option', ['as' => 'crmAccountEdit', 'uses' => 'Buisness\AccountsController@actionOptionSet']);
//workflow
Route::post('/workflow/',  ['as' => 'roteWorkflowIndex', 'uses' => 'Crm\WorkflowController@actionIndex']);

//Stock
Route::post('/stocks',  ['as' => 'companyStock', 'uses' => 'Buisness\CompanyController@showStock']);
Route::post('/stock',  ['as' => 'companyStockShow', 'uses' => 'Buisness\CompanyController@showStock']);
Route::patch('/stock',  ['as' => 'companyStockEdit', 'uses' => 'Buisness\CompanyController@editStock']);
Route::delete('/stock',  ['as' => 'companyStockEdit', 'uses' => 'Buisness\CompanyController@deleteStock']);
Route::post('/stock/add',  ['as' => 'companyStockAdd', 'uses' => 'Buisness\CompanyController@addStock']);

#Brigade
Route::post('/brigade',             ['as' => 'brigadeList', 'uses' => 'Crm\BrigadeController@actionList']);
Route::post('/brigade/add',         ['as' => 'brigadeNew', 'uses' => 'Crm\BrigadeController@actionNew']);
Route::patch('/brigade',            ['as' => 'brigadeEdit', 'uses' => 'Crm\BrigadeController@actionEdit']);
Route::delete('/brigade',           ['as' => 'brigadeDelete', 'uses' => 'Crm\BrigadeController@actionDelete']);
Route::post('/brigade/people',      ['as' => 'brigadePeopleList', 'uses' => 'Crm\BrigadeController@actionListPeople']);
Route::post('/brigade/people/free',      ['as' => 'brigadePeopleFreeList', 'uses' => 'Crm\BrigadeController@actionListPeopleFree']);
Route::post('/brigade/people/add',  ['as' => 'brigadePeopleNew', 'uses' => 'Crm\BrigadeController@actionNewPeople']);
Route::patch('/brigade/people',     ['as' => 'brigadePeopleEdit', 'uses' => 'Crm\BrigadeController@actionEditPeople']);
Route::delete('/brigade/people',    ['as' => 'brigadePeopleDelete', 'uses' => 'Crm\BrigadeController@actionDeletePeople']);

Route::post('/brigade/{idBrigade}/people',             ['as' => 'moveWorkpeopleBrigade', 'uses' => 'Crm\BrigadeController@actionListWorkpeopleBrigade']);

Route::post('/brigade/{idBrigade}/people/add',             ['as' => 'addWorkpeopleBrigade', 'uses' => 'Crm\BrigadeController@actionAddWorkpeopleBrigade']);
Route::patch('/brigade/{idBrigade}/people',             ['as' => 'moveWorkpeopleBrigade', 'uses' => 'Crm\BrigadeController@actionMoveWorkpeopleBrigade']);
Route::delete('/brigade/{idBrigade}/people',             ['as' => 'removeWorkpeopleBrigade', 'uses' => 'Crm\BrigadeController@actionRemoveWorkpeopleBrigade']);

Route::post('/brigade/{idBrigade}/specifications',             ['as' => 'listSpecificationsBrigade', 'uses' => 'Crm\BrigadeController@listSpecificationsBrigade']);
Route::delete('/brigade/{idBrigade}/specifications',             ['as' => 'listSpecificationsBrigade', 'uses' => 'Crm\BrigadeController@deleteSpecificationsBrigade']);
Route::post('/brigade/{idBrigade}/specification/set',             ['as' => 'setSpecificationsBrigade', 'uses' => 'Crm\BrigadeController@setSpecificationsBrigade']);


Route::post('/brigade/tabel/add',      ['as' => 'brigadeTabeladd', 'uses' => 'Crm\BrigadeController@actionTabelNew']);
Route::post('/brigade/tabel',      ['as' => 'brigadeTabellIST', 'uses' => 'Crm\BrigadeController@actionTabelList']);
Route::patch('/brigade/tabel',      ['as' => 'brigadeTabelEdit', 'uses' => 'Crm\BrigadeController@actionTabelEdit']);
Route::delete('/brigade/tabel',    ['as' => 'brigadeTabelDelete', 'uses' => 'Crm\BrigadeController@actionTabelDelete']);


//requisition
Route::post('/requisition/{requisitionId}/chat',      ['as' => 'chatListMessageRequisition', 'uses' => 'Crm\RequisitionController@chatListMessageRequisition']);
Route::delete('/requisition/{requisitionId}/chat',      ['as' => 'chatDeleteMessageRequisition', 'uses' => 'ChatController@actionDeleteMessage']);
Route::delete('/requisition/other/{requisitionId}/chat',      ['as' => 'chatDeleteOtherMessageRequisition', 'uses' => 'ChatController@actionDeleteMessage']);
Route::post('/requisition/{requisitionId}/chat/send',      ['as' => 'chatSendMessageRequisition', 'uses' => 'Crm\RequisitionController@chatSendMessageRequisition']);
Route::post('/requisition/other/{requisitionId}/chat',      ['as' => 'chatListMessageRequisitionOther', 'uses' => 'Crm\RequisitionController@chatListMessageRequisition']);
Route::post('/requisition/other/{requisitionId}/chat/send',      ['as' => 'chatSendMessageRequisitionOther', 'uses' => 'Crm\RequisitionController@chatSendMessageRequisition']);
Route::post('/requisition/{requisitionId}',      ['as' => 'getRequisition', 'uses' => 'Crm\RequisitionController@getRequisition']);

Route::post('/requisitions/{requisitionId}/set-status',  ['as' => 'requisitionMaterialCancel', 'uses' => 'Crm\\RequisitionController@requisitionSetStatus']);


Route::group([
    'prefix' => '/upravleine',
], function ($router) {
    Route::post('/', function (){
        return "Api v1 buisness crm/upravleine";
    });
    require(__DIR__.'/upravleine.php');
});
