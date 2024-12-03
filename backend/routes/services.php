<?php
use Illuminate\Support\Facades\Route;



#TODO Реализовать вывод списка файлов пользователя
Route::post('/files/my',  ['as' => 'listMyFilesCloud', 'uses' => 'ServicesController@listMyFilesCloud']);

Route::post('/file/{hash}/info',  ['as' => 'fileGet', 'uses' => 'ServicesController@actionFileInfo']);
Route::post('/file/info',  ['as' => 'fileGet', 'uses' => 'ServicesController@actionFileInfo']);

Route::get('/file/{hash}',  ['as' => 'fileGet', 'uses' => 'ServicesController@actionFileGet']);
Route::post('/file/',  ['as' => 'fileGet', 'uses' => 'ServicesController@actionFileGetJson']);


Route::post('/file/upload',  ['as' => 'fileLoad', 'uses' => 'ServicesController@actionFileLoad']);


Route::delete('/file',  ['as' => 'fileGet', 'uses' => 'ServicesController@actionFileDelete']);
