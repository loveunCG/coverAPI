<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::post('user/signup','API\AuthUserCtrl@signup');

Route::post('user/login','API\AuthUserCtrl@authenticate');

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'user', 'as' => 'user.'], function () {
  Route::post('update','API\AuthUserCtrl@updateUser');
});

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'agent', 'as' => 'agent.'], function () {
  Route::resource('/','API\AgentCtrl');
});
