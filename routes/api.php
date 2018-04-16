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
*/

Route::post('customer/create', 'API\ApiAuthUserCtrl@signup');
Route::post('user/login', 'API\ApiAuthUserCtrl@authenticate');
Route::post('user/sendSMS', 'API\ApiAuthUserCtrl@sendSMS');
Route::post('user/checkVerfityCode', 'API\ApiAuthUserCtrl@checkPhoneVerify');
Route::post('user/forgetPassword', 'Auth\ForgotPasswordController@sendEmailToken');
Route::post('user/resetPassword', 'Auth\ResetPasswordController@resetPassword');

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::post('update', 'API\ApiAuthUserCtrl@update');
    Route::post('referral', 'API\ApiAuthUserCtrl@getReferral');
    Route::post('getcompany', 'API\ApiAuthUserCtrl@getcompany');
});

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'jobs', 'as' => 'jobs.'], function () {
    Route::post('create', 'API\ApiCustomerController@addJob');
    Route::post('show', 'API\ApiCustomerController@fetchJob');
    Route::post('getInsuranceType', 'API\ApiCustomerController@getInsuranceType');
    Route::post('jobDetail', 'API\ApiCustomerController@jobDetail');
    Route::post('getQuotDocument', 'API\ApiCustomerController@getQuotDocument');
    Route::post('/handover', 'API\ApiAgentController@handOverJob');
    Route::post('/view', 'API\ApiAgentController@assignedJobView');
    Route::post('/action', 'API\ApiAgentController@jobAction');
    Route::post('/acceptAgent', 'API\ApiAgentController@acceptAgent');
    Route::post('/acceptedJobList', 'API\ApiAgentController@acceptedJobList');
    Route::post('/renewJob', 'API\ApiAgentController@renewJob');
    Route::post('/completeJob', 'API\ApiAgentController@completeJob');
    Route::post('/customerGetCompletedJob', 'API\ApiAgentController@customerGetCompletedJob');
});

Route::group(['middleware' => ['auth.jwt'], 'prefix' => 'agent', 'as' => 'agent.'], function () {
    Route::post('/assign', 'API\ApiAgentController@assignAgent');
    Route::post('/addQuotation', 'API\ApiAgentController@addQuotation');
    Route::post('/getQuotation', 'API\ApiAgentController@getQuotation');
    Route::post('/allview', 'API\ApiAgentController@allJobView');
    Route::post('/view/history', 'API\ApiAgentController@agentHistory');
    Route::post('/agentCompletedJob', 'API\ApiAgentController@agentCompletedJob');
    Route::post('/view/joblist', 'API\ApiCustomerController@fetchJob');
});

// independent API
Route::post('upload', 'API\ApiCustomerController@uploadFile');
Route::post('upload/documents', 'API\ApiCustomerController@addDocuments');
