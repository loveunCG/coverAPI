<?php

// Auth::routes();
Route::get('login', 'Auth\AdminLoginController@showLoginForm')->name('login');;
Route::post('login', 'Auth\AdminLoginController@login');
Route::post('logout', 'Auth\AdminLoginController@logout')->name('logout');
Route::get('password/reset', 'Auth\AdminForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('/', 'HomeController@index');
Route::get('/home', 'HomeController@index')->name('home');
// Route::post('/test', 'Agent\AgentMgCtrl@test');
// Route::group(['middleware' => ['auth'], 'prefix' => 'adminMg', 'as' => 'adminMg.'], function () {
//     Route::get('/', 'HomeController@adminMg');
//     Route::get('/getAdminTableInfo', 'Admin\AdminMgCtrl@getAdminTableInfo');
//     Route::get('/getAdminInfo/{id?}', 'Admin\AdminMgCtrl@getAdminInfo');
//     Route::post('/create/{id?}', 'Admin\AdminMgCtrl@createAdmin');
//     Route::post('/update', 'Admin\AdminMgCtrl@updateAdmin');
//     Route::post('/activate', 'Admin\AdminMgCtrl@activate');
//     Route::post('/resetPassword', 'Admin\AdminMgCtrl@resetPassword');
//     Route::post('/removeAdmin', 'Admin\AdminMgCtrl@removeAdmin');
//     Route::post('/duplicationEmail', 'Admin\AdminMgCtrl@duplicationEmail');
//     Route::get('/MessageMg/{id?}', 'userMgGate@MessageMg');
// });
//
// //agent manager page
//
// Route::group(['middleware' => ['auth'], 'prefix' => 'agentMg', 'as' => 'agentMg.'], function () {
//     Route::get('/', 'HomeController@agentMg');
//     Route::get('/getAgentTableInfo', 'Agent\AgentMgCtrl@getAgentTableInfo');
//     Route::get('/getAgentDetailInfo/{id}', 'Agent\AgentMgCtrl@getAgentDetailInfo');
//     Route::post('/test', 'Agent\AgentMgCtrl@test');
//     Route::post('/create/{id?}', 'Agent\AgentMgCtrl@create');
//     Route::post('/removeAgent', 'Agent\AgentMgCtrl@removeAgent');
//     Route::post('/resetPassword', 'Agent\AgentMgCtrl@resetPassword');
//     Route::post('/update/{id?}', 'Agent\AgentMgCtrl@update');
// });
//
// //customer manage page
//
// Route::group(['middleware' => ['auth'], 'prefix' => 'custom', 'as' => 'custom.'], function () {
//     Route::get('/', 'HomeController@customerMg');
//     Route::get('/getCustomTableInfo', 'customer\CustomerMgCtrl@getCustomTableInfo');
//     Route::get('/getCustomDetailInfo/{id?}', 'customer\CustomerMgCtrl@getCustomDetailInfo');
//     Route::post('/resetPassword', 'customer\CustomerMgCtrl@resetPassword');
//     Route::post('/removeCustomter/', 'customer\CustomerMgCtrl@removeCustomer');
// });
//
// // setting module
// Route::group(['middleware' => ['auth'], 'prefix' => 'setting', 'as' => 'setting.'], function () {
//     Route::get('/insurance', 'HomeController@insurance');
//     Route::get('/getInsuranceTableInfo', 'Setting\CommonCtrl@getInsuranceTable');
//     Route::get('/getInsurance/{id?}', 'Setting\CommonCtrl@getInsurance');
//     Route::post('/addInsurance/', 'Setting\CommonCtrl@addInsurance');
// });
