<?php
namespace routes;
use Illuminate\Support\Facades\Route;

Route::post('/material',  ['as' => 'material', 'uses' => 'Crm\MaterialController@actionList']);
Route::post('/material/history',  ['as' => 'material', 'uses' => 'Crm\MaterialController@actionMaterialHistory']);
Route::post('/material/add',  ['as' => 'materialnew', 'uses' => 'Crm\MaterialController@actionNew']);
Route::post('/material/all',  ['as' => 'materialAll', 'uses' => 'Crm\MaterialController@actionAll']);

Route::post('/material/{idMaterial:[0-9]+}',  ['as' => 'materialshow', 'uses' => 'Crm\MaterialController@actionShow']);
Route::patch('/material',  ['as' => 'materialEdit', 'uses' => 'Crm\MaterialController@actionEdit']);

Route::delete('/material/{idMaterial:[0-9]+}',  ['as' => 'materialdeleteget', 'uses' => 'Crm\MaterialController@actionDelete']);
Route::delete('/material',  ['as' => 'materialDelete', 'uses' => 'Crm\MaterialController@actionDelete']);

Route::post('/material/units',  ['as' => 'material', 'uses' => 'Crm\MaterialController@listUnits']);

Route::post('/material/search',  ['as' => 'material', 'uses' => 'Crm\MaterialController@actionSearch']);
Route::post('/material/unit/add',  ['as' => 'materialNewUnit', 'uses' => 'Crm\MaterialController@newUnit']);
//

Route::post('/contracts',  ['as' => 'd_workContractNew', 'uses' => 'Crm\\ContractsController@listContracts']);
Route::post('/contracts/work',  ['as' => 'd_workContractNew', 'uses' => 'Crm\\ContractsController@listWorkContracts']);
Route::post('/contracts/supply',  ['as' => 'd_workContractNew', 'uses' => 'Crm\\ContractsController@listSupplyContracts']);

Route::post('/contracts/work/add',  ['as' => 'd_workContractNew', 'uses' => 'Crm\\ContractsController@workContractNew']);
Route::post('/contracts/supply/add',  ['as' => 'd_supplyContractNew', 'uses' => 'Crm\\ContractsController@addSupplyContractNew']);

Route::delete('/contract/{idContract}',  ['as' => 'd_workContractUnFix', 'uses' => 'Crm\\ContractsController@deleteContractId']);
Route::patch('/contract/{idContract}',  ['as' => 'd_workContractNew', 'uses' => 'Crm\\ContractsController@editContractId']);
Route::patch('/contracts/{idContract}',  ['as' => 'd_workContractNew_s', 'uses' => 'Crm\\ContractsController@editContractId']);
Route::post('/contract/{idContract}/fix',  ['as' => 'd_workContractFix', 'uses' => 'Crm\\ContractsController@fixContractId']);
Route::post('/contract/{idContract}/unfix',  ['as' => 'd_workContractUnFix', 'uses' => 'Crm\\ContractsController@unFixContractId']);
Route::post('/contract/{contractId}/specifications/',  ['as' => 'listSpecificationContract', 'uses' => 'Crm\\ContractsController@listSpecificationContract']);
Route::post('/contract/{contractId}/specification/{specificationId}/add',  ['as' => 'addSpecificationContract', 'uses' => 'Crm\\ContractsController@addSpecificationContract']);
Route::post('/contract/{contractId}/specification/{specificationId}/remove',  ['as' => 'removeSpecificationContract', 'uses' => 'Crm\\ContractsController@removeSpecificationContract']);


Route::post('/banki',  ['as' => 'bankilist', 'uses' => 'Crm\BankController@actionList']);
Route::post('/banki/add',  ['as' => 'bankinew', 'uses' => 'Crm\BankController@actionNew']);
Route::post('/banki/search',  ['as' => 'bankisearch', 'uses' => 'Crm\BankController@actionSearch']);
Route::post('/banki/loadcbr',  ['as' => 'bankiloadcbr', 'uses' => 'Crm\BankController@actionLoadCbr']);

Route::post('/partner',  ['as' => 'partnerList', 'uses' => 'Crm\PartnersController@actionList']);
Route::post('/partner/add',  ['as' => 'partnerAdd', 'uses' => 'Crm\PartnersController@actionNew']);
Route::post('/partner/loadinn',  ['as' => 'partnerInn', 'uses' => 'Crm\PartnersController@actionLoadInn']);
Route::patch('/partner',  ['as' => 'partnerEdit', 'uses' => 'Crm\PartnersController@actionEdit']);
Route::delete('/partner',  ['as' => 'partnerDelete', 'uses' => 'Crm\PartnersController@actionDelete']);
Route::post('/partner/search',  ['as' => 'material', 'uses' => 'Crm\PartnersController@actionSearch']);

Route::post('/partner/{idPartner}/bank/accounts',  ['as' => 'partnerNewBankAccount', 'uses' => 'Crm\PartnersController@actionListBankAccount']);
Route::post('/partner/{idPartner}/bank/account/add',  ['as' => 'partnerNewBankAccount', 'uses' => 'Crm\PartnersController@actionAddBankAccount']);
Route::delete('/partner/{idPartner}/bank/account',  ['as' => 'partnerNewBankAccount', 'uses' => 'Crm\PartnersController@actionDeleteBankAccount']);


Route::post('/profession',  ['as' => 'professionList', 'uses' => 'Crm\ProfessionController@actionList']);
Route::post('/profession/add',  ['as' => 'professionAdd', 'uses' => 'Crm\ProfessionController@actionNew']);
Route::post('/profession/new',  ['as' => 'professionNew', 'uses' => 'Crm\ProfessionController@actionNew']);
Route::patch('/profession',  ['as' => 'professionEdit', 'uses' => 'Crm\ProfessionController@actionEdit']);
Route::delete('/profession',  ['as' => 'professionDelete', 'uses' => 'Crm\ProfessionController@actionDelete']);



Route::post('/people',      ['as' => 'WorkpeopleListOld', 'uses' => 'Crm\WorkpeopleController@actionList']);

Route::post('/people/add',  ['as' => 'WorkpeopleNewOld', 'uses' => 'Crm\WorkpeopleController@actionNew']);
Route::post('/people/{idWorkPeople}/account',  ['as' => 'WorkpeopleSetAccountOld', 'uses' => 'Crm\WorkpeopleController@actionGetAccountWorkPeople']);
//actionGetAccountWorkPeople
Route::patch('/people',     ['as' => 'WorkpeopleEditOld', 'uses' => 'Crm\WorkpeopleController@actionEdit']);
Route::delete('/people',    ['as' => 'WorkpeopleDeleteOld', 'uses' => 'Crm\WorkpeopleController@actionDelete']);


Route::post('/workpeople',      ['as' => 'WorkpeopleList', 'uses' => 'Crm\WorkpeopleController@actionList']);
Route::post('/workpeople/add',  ['as' => 'WorkpeopleAdd', 'uses' => 'Crm\WorkpeopleController@actionNew']);
Route::post('/workpeople/new',  ['as' => 'WorkpeopleNew', 'uses' => 'Crm\WorkpeopleController@actionNew']);
Route::patch('/workpeople',     ['as' => 'WorkpeopleEdit', 'uses' => 'Crm\WorkpeopleController@actionEdit']);
Route::delete('/workpeople',    ['as' => 'WorkpeopleDelete', 'uses' => 'Crm\WorkpeopleController@actionDelete']);

Route::post('/workpeople/{idWorkPeople}/account',  ['as' => 'WorkpeopleSetAccount', 'uses' => 'Crm\WorkpeopleController@actionGetAccountWorkPeople']);
Route::get('/workpeople/{idWorkpeople}',      ['as' => 'getIdWorkPeopleG', 'uses' => 'Crm\WorkpeopleController@actionGetIdWorkPeople']);
Route::post('/workpeople/{idWorkpeople}',      ['as' => 'getIdWorkPeople', 'uses' => 'Crm\WorkpeopleController@actionGetIdWorkPeople']);
Route::post('/workpeople/{idWorkpeople}/set/master',  ['as' => 'WorkpeopleSetMaster', 'uses' => 'Crm\WorkpeopleController@actionSetMaster']);

/*
Route::post('/people/tabel/add',      ['as' => 'WorkpeopleTabeladd', 'uses' => 'Crm\WorkpeopleController@actionTabelNew']);
Route::post('/people/tabel',      ['as' => 'WorkpeopleTabellIST', 'uses' => 'Crm\WorkpeopleController@actionTabelList']);
Route::patch('/people/tabel',      ['as' => 'WorkpeopleTabelEdit', 'uses' => 'Crm\WorkpeopleController@actionTabelEdit']);
Route::delete('/people/tabel',    ['as' => 'WorkpeopleTabelDelete', 'uses' => 'Crm\WorkpeopleController@actionTabelDelete']);
*/


Route::post('/stock',  ['as' => 'companyStock', 'uses' => 'Buisness\CompanyController@showStock']);
Route::post('/stock/',  ['as' => 'companyStockShow', 'uses' => 'Buisness\CompanyController@showStock']);
Route::post('/stock/add',  ['as' => 'companyStockAdd', 'uses' => 'Buisness\CompanyController@addStock']);
Route::patch('/stock/',  ['as' => 'companyStockEdit', 'uses' => 'Buisness\CompanyController@editStock']);
