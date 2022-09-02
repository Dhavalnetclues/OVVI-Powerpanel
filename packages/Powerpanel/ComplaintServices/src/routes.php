<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/complaint-services/', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@index')->name('powerpanel.complaint-services.list');
    Route::get('powerpanel/complaint-services/', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@index')->name('powerpanel.complaint-services.index');
    
    Route::post('powerpanel/complaint-services/get_list/', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@get_list')->name('powerpanel.complaint-services.get_list');
    Route::post('powerpanel/complaint-services/get_list_New/', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@get_list_New')->name('powerpanel.complaint-services.get_list_New');
    Route::post('powerpanel/complaint-services/get_list_draft/', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@get_list_draft')->name('powerpanel.complaint-services.get_list_draft');
    Route::post('powerpanel/complaint-services/get_list_trash/', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@get_list_trash')->name('powerpanel.complaint-services.get_list_trash');
    Route::post('powerpanel/complaint-services/get_list_favorite/', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@get_list_favorite')->name('powerpanel.complaint-services.get_list_favorite');

    Route::get('powerpanel/complaint-services/add/', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@edit')->name('powerpanel.complaint-services.add');
    Route::post('powerpanel/complaint-services/add/', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@handlePost')->name('powerpanel.complaint-services.add');

    Route::get('/powerpanel/complaint-services/{alias}/edit', ['as' => 'powerpanel.complaint-services.edit', 'uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@edit', 'middleware' => 'permission:complaint-services-edit']);
    Route::post('/powerpanel/complaint-services/{alias}/edit', ['as' => 'powerpanel.complaint-services.handleEditPost', 'uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@handlePost', 'middleware' => 'permission:complaint-services-edit']);

    Route::post('powerpanel/complaint-services/DeleteRecord', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@DeleteRecord');
    Route::post('powerpanel/complaint-services/publish', ['uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@publish', 'middleware' => 'permission:complaint-services-edit']);
    Route::post('powerpanel/complaint-services/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@reorder', 'middleware' => 'permission:complaint-services-list']);
    Route::post('powerpanel/complaint-services/addpreview', ['as' => 'powerpanel.complaint-services.addpreview', 'uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@addPreview', 'middleware' => 'permission:complaint-services-create']);             
    Route::post('powerpanel/complaint-services/getChildData', ['uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@getChildData', 'middleware' => 'permission:complaint-services-list']);
    Route::post('powerpanel/complaint-services/ApprovedData_Listing', ['uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@ApprovedData_Listing', 'middleware' => 'permission:complaint-services-list']);
    Route::post('powerpanel/complaint-services/getChildData_rollback', ['uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@getChildData_rollback']);
    Route::post('powerpanel/complaint-services/insertComents', ['uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@insertComents']);
    Route::post('powerpanel/complaint-services/Get_Comments', ['uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@Get_Comments']);
    Route::post('powerpanel/complaint-services/rollback-record', ['uses' => 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@rollBackRecord', 'middleware' => 'permission:complaint-services-list']);
     Route::post('powerpanel/complaint-services/get_builder_list', 'Powerpanel\ComplaintServices\Controllers\Powerpanel\ComplaintServicesController@get_buider_list');
    
});
