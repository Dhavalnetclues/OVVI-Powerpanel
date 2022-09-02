<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/register-application/', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@index')->name('powerpanel.register-application.list');
    Route::get('powerpanel/register-application/', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@index')->name('powerpanel.register-application.index');
    
    Route::post('powerpanel/register-application/get_list/', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@get_list')->name('powerpanel.register-application.get_list');
    Route::post('powerpanel/register-application/get_list_New/', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@get_list_New')->name('powerpanel.register-application.get_list_New');
    Route::post('powerpanel/register-application/get_list_draft/', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@get_list_draft')->name('powerpanel.register-application.get_list_draft');
    Route::post('powerpanel/register-application/get_list_trash/', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@get_list_trash')->name('powerpanel.register-application.get_list_trash');
    Route::post('powerpanel/register-application/get_list_favorite/', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@get_list_favorite')->name('powerpanel.register-application.get_list_favorite');

    Route::get('powerpanel/register-application/add/', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@edit')->name('powerpanel.register-application.add');
    Route::post('powerpanel/register-application/add/', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@handlePost')->name('powerpanel.register-application.add');

    Route::get('/powerpanel/register-application/{alias}/edit', ['as' => 'powerpanel.register-application.edit', 'uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@edit', 'middleware' => 'permission:register-application-edit']);
    Route::post('/powerpanel/register-application/{alias}/edit', ['as' => 'powerpanel.register-application.handleEditPost', 'uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@handlePost', 'middleware' => 'permission:register-application-edit']);


    Route::post('powerpanel/register-application/DeleteRecord', 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@DeleteRecord');
    Route::post('powerpanel/register-application/publish', ['uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@publish', 'middleware' => 'permission:register-application-edit']);
    Route::post('powerpanel/register-application/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@reorder', 'middleware' => 'permission:register-application-list']);
    Route::post('powerpanel/register-application/addpreview', ['as' => 'powerpanel.register-application.addpreview', 'uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@addPreview', 'middleware' => 'permission:register-application-create']);             
    Route::post('powerpanel/register-application/getChildData', ['uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@getChildData', 'middleware' => 'permission:register-application-list']);
    Route::post('powerpanel/register-application/ApprovedData_Listing', ['uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@ApprovedData_Listing', 'middleware' => 'permission:register-application-list']);
    Route::post('powerpanel/register-application/getChildData_rollback', ['uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@getChildData_rollback']);
    Route::post('powerpanel/register-application/insertComents', ['uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@insertComents']);
    Route::post('powerpanel/register-application/Get_Comments', ['uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@Get_Comments']);
    Route::post('powerpanel/register-application/rollback-record', ['uses' => 'Powerpanel\RegisterApplication\Controllers\Powerpanel\RegisterApplicationController@rollBackRecord', 'middleware' => 'permission:register-application-list']);
    Route::post('powerpanel/register-application/get_builder_list', 'Powerpanel\RegisterApplication\Controllers\powerpanel\RegisterApplicationController@get_buider_list');
    
});

Route::get('uploadRegisterApplicationCSV', 'Powerpanel\RegisterApplication\Controllers\RegisterApplicationCSVController@uploadCSV');
