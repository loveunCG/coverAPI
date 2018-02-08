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

Route::post('user/signup', 'API\ApiAuthUserCtrl@signup');
Route::post('upload/documents', 'API\ApiCustomerController@addDocuments');
Route::post('user/login', 'API\ApiAuthUserCtrl@authenticate');
Route::post('user/sendSMS', 'API\ApiAuthUserCtrl@sendSMS');
Route::post('user/checkVerfityCode', 'API\ApiAuthUserCtrl@checkPhoneVerify');
Route::post('user/forgetPassword', 'Auth\ForgotPasswordController@sendEmailToken');
Route::post('user/resetPassword', 'Autn\ResetPasswordController@resetPassword');

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::post('update', 'API\ApiAuthUserCtrl@update');
});

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'jobs', 'as' => 'jobs.'], function () {
    Route::post('create', 'API\ApiCustomerController@addJob');
    Route::post('show', 'API\ApiCustomerController@fetchJob');
    Route::post('/', 'API\ApiCustomerController@fetchJob');
    Route::post('getInsuranceType', 'API\ApiCustomerController@getInsuranceType');
});

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'agent', 'as' => 'agent.'], function () {
    Route::post('/assign', 'API\ApiAgentController@assignAgent');
    Route::post('/job/handover', 'API\ApiAgentController@handOverJob');
    Route::post('/job/view', 'API\ApiAgentController@assignedJobView');
    Route::post('/job/action', 'API\ApiAgentController@jobAction');
    Route::post('/job/assignedJoblist', 'API\ApiAgentController@assignedJobList');
    Route::post('/all/job/view/', 'API\ApiAgentController@allJobView');
    Route::post('/view/history', 'API\ApiAgentController@agentHistory');
    Route::post('/view/joblist', 'API\ApiAgentController@agentJobList');
});

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'agent', 'as' => 'agent.'], function () {
});


// independent API
Route::post('upload', 'API\ApiCustomerController@uploadFile');
Route::post('job', 'API\ApiCustomerController@jobDetail');
