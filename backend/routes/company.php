<?php
use Illuminate\Support\Facades\Route;

Route::post('/company',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@showCompany']);
//Route::post('/company/{idCompany}',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@showCompany']);
Route::patch('/company',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@editCompany']);
Route::delete('/company',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@delCompany']);
Route::post('/company/add',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@addCompany']);

Route::post('/company/stock',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@showStock']);
//Route::post('/company/stock/{idStock}',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@showStock']);
Route::delete('/company/stock',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@deleteStock']);
Route::patch('/company/stock',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@editStock']);

Route::post('/company/stock/add',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@addStock']);
//Route::post('/company/stock',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@showMyCompnayStocks']);


Route::post('/company/account',  ['as' => 'company', 'uses' => 'Buisness\CompanyController@showAccounts']);

