<?php


Route::get('/', 'HomeController@index');
Route::get('home', 'HomeController@index');
Route::get('login', function () {return view('pages.login');});
Route::get('logout', 'HomeController@logout');
Route::get('forgot-password', function () {return view('pages.forgot-password');});
Route::get('home/dashboard', 'HomeController@showDashboard');
Route::post('authenticate', 'HomeController@connected');

Route::get('report/getAllReports', 'ReportingController@getAllReports');
Route::get('report/getAllReportTemplates', 'ReportingController@getAllReportTemplates');
Route::get('report/deleteReport/{report_id}', 'ReportingController@deleteReport');
Route::post('saveReport', 'HomeController@saveReport');
Route::post('saveReportTemplate', 'ReportingController@saveReportTemplate');
Route::post('register', 'AppController@saveApps');
Route::post('updateCurrentApp', 'AppController@updateCurrentApp');
Route::post('saveEmailTemplate', 'ReportingController@saveEmailTemplate');
Route::get('reportTemplate', 'HomeController@getReportTemplate');

Route::get('email-account', 'HomeController@getEmailAccounts');
Route::get('mailing-list', 'HomeController@getMailingList');
Route::get('email', 'HomeController@getMailList');
Route::get('programmed-email', 'HomeController@getProgrammedMailList');

Route::post('saveEmailAccount', 'HomeController@saveEmailAccount');
Route::post('saveMailingList', 'HomeController@saveMailingList');
Route::post('saveProgrammedEmail', 'HomeController@saveProgrammedEmail');


/********************** Email send restful *********************************/
Route::post('mail/send', 'ApiController@simpleEmailSend');

/********************* Restful urls report***********************************/
Route::get('download/{key}/{title}', 'ApiController@downloadPdfReport');
Route::post('report/generate', 'ApiController@generateReport');





