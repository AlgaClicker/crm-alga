<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::post('/',  ['as' => 'chatList', 'uses' => 'ChatController@actionListAccountChat']);
Route::delete('/',  ['as' => 'chatList', 'uses' => 'ChatController@actionDeleteMessage']);
Route::post('/send',  ['as' => 'chatSendMessage', 'uses' => 'ChatController@actionSendMessages']);
Route::post('/list',  ['as' => 'chatListAccounts', 'uses' => 'ChatController@actionListAccountChat']);
Route::post('/{uuidAccount}',  ['as' => 'chatSendMessage', 'uses' => 'ChatController@actionPrivateChat']);
Route::post('/read/{uuidChat}',  ['as' => 'chatSendMessage', 'uses' => 'ChatController@actionReadedChat']);



