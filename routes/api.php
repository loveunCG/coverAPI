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
Route::post('user/signup', 'API\AuthUserCtrl@signup');
Route::post('job', 'API\ApiJobController@jobDetail');

Route::post('upload/documents', 'API\ApiJobController@addDocuments');
Route::post('upload', 'API\ApiJobController@uploadFile');
Route::post('user/login', 'API\AuthUserCtrl@authenticate');
Route::post('user/sendSMS', 'API\AuthUserCtrl@sendSMS');
Route::post('user/checkVerfityCode', 'API\AuthUserCtrl@checkPhoneVerify');
Route::post('user/forgetPassword', 'Auth\ForgotPasswordController@sendEmailToken');
Route::post('user/resetPassword', 'Autn\ResetPasswordController@resetPassword');

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::post('update', 'API\AuthUserCtrl@updateUser');
});

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'jobs', 'as' => 'jobs.'], function () {
    Route::post('create', 'API\ApiJobController@addJob');
    Route::post('show', 'API\ApiJobController@fetchJob');
    Route::post('/', 'API\ApiJobController@fetchJob');
    Route::post('getInsuranceType', 'API\ApiJobController@getInsuranceType');
});

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'agent', 'as' => 'agent.'], function () {
    Route::post('/assign', 'API\ApiAgentController@assignAgent');
    Route::post('/job/handover', 'API\ApiAgentController@handOverJob');
    Route::post('/job/view', 'API\ApiAgentController@assignedJobView');
    Route::post('/job/action', 'API\ApiAgentController@jobAction');
    Route::post('/all/job/view/', 'API\ApiAgentController@allJobView');
    Route::post('/view/history', 'API\ApiAgentController@agentHistory');
    Route::post('/view/joblist', 'API\ApiAgentController@agentJobList');
});

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'agent', 'as' => 'agent.'], function () {
});
