<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::get('/message', 'App\Http\Controllers\api\messageBoardController@getAllMessage');
Route::get('/message/{id}', 'App\Http\Controllers\api\messageBoardController@getMessageById');
Route::get('/message/search/keyword', 'App\Http\Controllers\api\messageBoardController@searchMessage');
Route::post('/message', 'App\Http\Controllers\api\messageBoardController@createMessage');
Route::put('/message/{id}', 'App\Http\Controllers\api\messageBoardController@updateMessage');
Route::delete('/message/{id}', 'App\Http\Controllers\api\messageBoardController@deleteMessage');
