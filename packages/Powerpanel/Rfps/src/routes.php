<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/rfps/', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@index')->name('powerpanel.rfps.list');
    Route::get('powerpanel/rfps/', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@index')->name('powerpanel.rfps.index');

    Route::post('powerpanel/rfps/get_list/', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@get_list')->name('powerpanel.rfps.get_list');
    Route::post('powerpanel/rfps/get_list_New/', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@get_list_New')->name('powerpanel.rfps.get_list_New');
    Route::post('powerpanel/rfps/get_list_draft/', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@get_list_draft')->name('powerpanel.rfps.get_list_draft');
    Route::post('powerpanel/rfps/get_list_trash/', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@get_list_trash')->name('powerpanel.rfps.get_list_trash');
    Route::post('powerpanel/rfps/get_list_favorite/', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@get_list_favorite')->name('powerpanel.rfps.get_list_favorite');

    Route::get('powerpanel/rfps/add/', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@edit')->name('powerpanel.rfps.add');
    Route::post('powerpanel/rfps/add/', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@handlePost')->name('powerpanel.rfps.add');

    Route::get('/powerpanel/rfps/{alias}/edit', ['as' => 'powerpanel.rfps.edit', 'uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@edit', 'middleware' => 'permission:rfps-edit']);
    Route::post('/powerpanel/rfps/{alias}/edit', ['as' => 'powerpanel.rfps.handleEditPost', 'uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@handlePost', 'middleware' => 'permission:rfps-edit']);

    Route::post('powerpanel/rfps/DeleteRecord', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@DeleteRecord');
    Route::post('powerpanel/rfps/publish', ['uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@publish', 'middleware' => 'permission:rfps-edit']);
    Route::post('powerpanel/rfps/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@reorder', 'middleware' => 'permission:rfps-list']);
    Route::post('powerpanel/rfps/addpreview', ['as' => 'powerpanel.rfps.addpreview', 'uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@addPreview', 'middleware' => 'permission:rfps-create']);       
    Route::post('powerpanel/rfps/getChildData', ['uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@getChildData', 'middleware' => 'permission:rfps-list']);
    Route::post('powerpanel/rfps/ApprovedData_Listing', ['uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@ApprovedData_Listing', 'middleware' => 'permission:rfps-list']);
    Route::post('powerpanel/rfps/getChildData_rollback', ['uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@getChildData_rollback']);
    Route::post('powerpanel/rfps/insertComents', ['uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@insertComents']);
    Route::post('powerpanel/rfps/Get_Comments', ['uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@Get_Comments']);
    Route::post('powerpanel/rfps/get_builder_list', 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@get_buider_list');

    Route::post('powerpanel/rfps/rollback-record', ['uses' => 'Powerpanel\Rfps\Controllers\Powerpanel\RfpsController@rollBackRecord', 'middleware' => 'permission:rfps-list']);
});
