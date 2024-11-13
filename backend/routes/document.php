<?php
namespace routes;
use Illuminate\Support\Facades\Route;

# Эндпоинты документооборота

Route::post('/',  ['as' => 'listDocument', 'uses' => 'DocumentsController@actionListDocument']);
Route::post('/add',  ['as' => 'newDocument', 'uses' => 'DocumentsController@actionNewDocument']);
Route::patch('/',  ['as' => 'editDocument', 'uses' => 'DocumentsController@actionEditDocument']);
Route::delete('/',  ['as' => 'deleteDocument', 'uses' => 'DocumentsController@actionDeleteDocument']);

Route::post('/task/add',  ['as' => 'newDocumentTask', 'uses' => 'Crm\WorkflowController@actionNewDocumentTask']);
Route::post('/task/list',  ['as' => 'listDocumentTask', 'uses' => 'Crm\WorkflowController@actionListDocumentTask']);


Route::post('/people/tabel/add',      ['as' => 'WorkpeopleTabeladd', 'uses' => 'Crm\WorkpeopleController@actionTabelNew']);
Route::post('/people/tabel',      ['as' => 'WorkpeopleTabellIST', 'uses' => 'Crm\WorkpeopleController@actionTabelList']);
Route::patch('/people/tabel',      ['as' => 'WorkpeopleTabelEdit', 'uses' => 'Crm\WorkpeopleController@actionTabelEdit']);
Route::delete('/people/tabel',    ['as' => 'WorkpeopleTabelDelete', 'uses' => 'Crm\WorkpeopleController@actionTabelDelete']);
