<?php
# Маршруты отчетов
use Illuminate\Support\Facades\Route;

Route::post('/',  function () {
    return "reporting";
});
Route::post('income',  ['as' => 'invoiceList', 'uses' => 'Buisness\ReportingController@actionIncome']);
Route::post('/brigades',  ['as' => 'reportingBrigades', 'uses' => 'Buisness\ReportingController@repostBrigades']);

Route::post('/accounts/active',  ['as' => 'reportingAccountsActive', 'uses' => 'Buisness\ReportingController@reportAccountsActive']);

Route::post('/specification',  ['as' => 'reportingBrigades', 'uses' => 'Buisness\ReportingController@reportSpecification']);
//
