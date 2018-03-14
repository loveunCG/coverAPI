<?php


Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('login');;
Route::post('login', 'Auth\AdminLoginController@login');
Route::post('logout', 'Auth\AdminLoginController@logout')->name('logout');
Route::get('password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
Route::group(['middleware' => ['auth:admin'], 'prefix' => 'adminMg', 'as' => 'adminMg.'], function () {
    Route::get('/', 'HomeController@adminMg');
    Route::get('/getAdminTableInfo', 'Web\AdminController@getAdminTableInfo');
    Route::get('/getAdminInfo/{id?}', 'Web\AdminController@getAdminInfo');
    Route::post('/create', 'Web\AdminController@createAdmin');
    Route::post('/update', 'Web\AdminController@updateAdmin');
    Route::post('/activate', 'Web\AdminController@activate');
    Route::post('/resetPassword', 'Web\AdminController@resetPassword');
    Route::post('/removeAdmin', 'Web\AdminController@removeAdmin');
    Route::get('/duplicationEmail', 'Web\AdminController@duplicationEmail');
    Route::get('/getauthuser', 'Web\AdminController@getAuthuserInfo');
    Route::post('/changePassword', 'Web\AdminController@changePassword');
    Route::get('/MessageMg/{id?}', 'userMgGate@MessageMg');
});

//agent manager page

Route::group(['middleware' => ['auth:admin'], 'prefix' => 'agentMg', 'as' => 'agentMg.'], function () {
    Route::get('/', 'HomeController@agentMg');
    Route::get('/getAgentTableInfo', 'Web\UserController@getAgentTableInfo');
    Route::get('/getAgentDetailInfo/{id}', 'Web\UserController@getAgentDetailInfo');
    Route::post('/create/{id?}', 'Web\UserController@create');
    Route::post('/removeAgent', 'Web\UserController@removeAgent');
    Route::post('/resetPassword', 'Web\UserController@resetPassword');
    Route::post('/update/{id?}', 'Web\UserController@update');
});
//
//customer manage page

Route::group(['middleware' => ['auth:admin'], 'prefix' => 'custom', 'as' => 'custom.'], function () {
    Route::get('/', 'HomeController@customerMg');
    Route::get('/getCustomTableInfo', 'Web\UserController@getCustomTableInfo');
    Route::get('/getCustomDetailInfo/{id?}', 'Web\UserController@getCustomDetailInfo');
    Route::post('/resetPassword', 'Web\UserController@resetPassword');
    Route::post('/removeCustomter', 'Web\UserController@removeCustomer');
});
// setting module

Route::group(['middleware' => [], 'prefix' => 'setting', 'as' => 'setting.'], function () {
    Route::get('/insurance', 'HomeController@insurance');
    Route::get('/company', 'HomeController@company');
    Route::get('/getInsuranceTableInfo', 'Web\SettingController@getInsuranceTable');
    Route::get('/getCompanyTableInfo', 'Web\SettingController@getCompanyTableInfo');
    Route::get('/getInsurance/{id?}', 'Web\SettingController@getInsurance');
    Route::get('/getCompany/{id?}', 'Web\SettingController@getCompany');
    Route::post('/addInsurance', 'Web\SettingController@addInsurance');
    Route::post('/addCompanyInfo', 'Web\SettingController@addCompany');
});

Route::group(['middleware' => [], 'prefix' => 'jobs', 'as' => 'jobs.'], function () {
    Route::get('/', 'HomeController@jobsMg');
    Route::get('/getJobListData', 'Web\JobsController@getJobList');
    Route::get('/getjobdetail/{id?}', 'Web\JobsController@JobDetailView');
    Route::get('/getQuotationList/{id?}', 'Web\JobsController@getQuotationList');
    Route::get('/getDocumentList/{id?}', 'Web\JobsController@getDocumentList');
    Route::get('/getAssignedJobList/{id?}', 'Web\JobsController@getAssignedJobList');
    Route::post('/removeJob', 'Web\JobsController@removeJob');
});
