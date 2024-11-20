<?php
use Illuminate\Support\Facades\Route;


Route::post('/all',  ['as' => 'notification', 'uses' => 'NotificationController@actionIndex']);
Route::post('/',  ['as' => 'notification', 'uses' => 'NotificationController@actionNoRead']);
Route::post('/send',  ['as' => 'notification', 'uses' => 'NotificationController@actionSend']);
Route::get('/send',  ['as' => 'notification', 'uses' => 'NotificationController@actionSendTemplate']);
Route::post('/{idNotification}/read',  ['as' => 'notification', 'uses' => 'NotificationController@actionRead']);
