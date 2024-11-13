<?php
namespace routes;
use Illuminate\Support\Facades\Route;

Route::group([
    'prefix' => 'accounts',
], function ($router) {
    Route::post('/', ['as' => 'accounts', 'uses' => 'Buisness\AccountsController@actionIndex']);
    Route::post('/add', ['as' => 'accountNew', 'uses' => 'Buisness\AccountsController@actionNewAccount']);
    //Route::post('/{idAccount}',  ['as' => 'accounts', 'uses' => 'Buisness\AccountsController@actionIndex']);
    //Route::post('/{idAccount}/history',  ['as' => 'accounts', 'uses' => 'Buisness\AccountsController@actionHistory']);
    //Route::delete('/{idAccount}',  ['as' => 'accounts', 'uses' => 'Buisness\AccountsController@actionAccountDel']);
    //Route::patch('/{idAccount}',  ['as' => 'accounts', 'uses' => 'Buisness\AccountsController@actionSave']);
    //Route::post('/roles/',  ['as' => 'accounts', 'uses' => 'Buisness\AccountsController@actionListRoles']);
    Route::post('/company/',  ['as' => 'accountList', 'uses' => 'Buisness\AccountsController@actionListCompanyAccount']);
    Route::post('/roles/',  ['as' => 'accountRoles', 'uses' => 'Buisness\AccountsController@actionListRoles']);
    Route::patch('/roles',  ['as' => 'accountRolesEdit', 'uses' => 'Buisness\AccountsController@actionUpdateRole']);
    Route::post('/roles/add',  ['as' => 'accounts', 'uses' => 'Buisness\AccountsController@actionAddRoles']);

    Route::put('/roles',  ['as' => 'accountRolesEdit', 'uses' => 'Buisness\AccountsController@actionUpdateRole']);
    Route::post('/option', ['as' => 'accountOptions', 'uses' => 'Buisness\AccountsController@actionOption']);

    Route::patch('/option', ['as' => 'accountOptionsSet', 'uses' => 'Buisness\AccountsController@actionOptionSet']);

    Route::post('/online', ['as' => 'accountOnline', 'uses' => 'Buisness\AccountsController@online']);
});
