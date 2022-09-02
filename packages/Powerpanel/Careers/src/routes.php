<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/careers/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@index')->name('powerpanel.careers.list');
    Route::get('powerpanel/careers/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@index')->name('powerpanel.careers.index');
    
    Route::post('powerpanel/careers/get_list/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@get_list')->name('powerpanel.careers.get_list');
    Route::post('powerpanel/careers/get_list_New/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@get_list_New')->name('powerpanel.careers.get_list_New');
    Route::post('powerpanel/careers/get_list_draft/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@get_list_draft')->name('powerpanel.careers.get_list_draft');
    Route::post('powerpanel/careers/get_list_trash/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@get_list_trash')->name('powerpanel.careers.get_list_trash');
    Route::post('powerpanel/careers/get_list_favorite/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@get_list_favorite')->name('powerpanel.careers.get_list_favorite');

    Route::get('powerpanel/careers/add/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@edit')->name('powerpanel.careers.add');
    Route::post('powerpanel/careers/add/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@handlePost')->name('powerpanel.careers.add');

    Route::get('/powerpanel/careers/{alias}/edit', ['as' => 'powerpanel.careers.edit', 'uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@edit', 'middleware' => 'permission:careers-edit']);
    Route::post('/powerpanel/careers/{alias}/edit', ['as' => 'powerpanel.careers.handleEditPost', 'uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@handlePost', 'middleware' => 'permission:careers-edit']);

    Route::post('powerpanel/careers/DeleteRecord', 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@DeleteRecord');
    Route::post('powerpanel/careers/publish', ['uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@publish', 'middleware' => 'permission:careers-edit']);
    Route::post('powerpanel/careers/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@reorder', 'middleware' => 'permission:careers-list']);
    Route::post('powerpanel/careers/addpreview', ['as' => 'powerpanel.careers.addpreview', 'uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@addPreview', 'middleware' => 'permission:careers-create']);             
    Route::post('powerpanel/careers/getChildData', ['uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@getChildData', 'middleware' => 'permission:careers-list']);
    Route::post('powerpanel/careers/ApprovedData_Listing', ['uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@ApprovedData_Listing', 'middleware' => 'permission:careers-list']);
    Route::post('powerpanel/careers/getChildData_rollback', ['uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@getChildData_rollback']);
    Route::post('powerpanel/careers/insertComents', ['uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@insertComents']);
    Route::post('powerpanel/careers/Get_Comments', ['uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@Get_Comments']);
    Route::post('powerpanel/careers/rollback-record', ['uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersController@rollBackRecord', 'middleware' => 'permission:careers-list']);

    Route::get('powerpanel/careers-lead/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersLeadController@index')->name('powerpanel.careers-lead.list');
    Route::get('powerpanel/careers-lead/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersLeadController@index')->name('powerpanel.careers-lead.index');

    Route::post('powerpanel/careers-lead/get_list/', 'Powerpanel\Careers\Controllers\Powerpanel\CareersLeadController@get_list')->name('powerpanel.careers-lead.get_list');
    Route::get('/powerpanel/careers-lead/ExportRecord', ['uses' => 'Powerpanel\Careers\Controllers\Powerpanel\CareersLeadController@ExportRecord', 'middleware' => 'permission:careers-lead-list']);
    Route::post('powerpanel/careers-lead/DeleteRecord', 'Powerpanel\Careers\Controllers\Powerpanel\CareersLeadController@DeleteRecord');
});
