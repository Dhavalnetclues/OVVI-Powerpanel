<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/fmbroadcasting/', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@index')->name('powerpanel.fmbroadcasting.list');
    Route::get('powerpanel/fmbroadcasting/', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@index')->name('powerpanel.fmbroadcasting.index');
    
    Route::post('powerpanel/fmbroadcasting/get_list/', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@get_list')->name('powerpanel.fmbroadcasting.get_list');
    Route::post('powerpanel/fmbroadcasting/get_list_New/', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@get_list_New')->name('powerpanel.fmbroadcasting.get_list_New');
    Route::post('powerpanel/fmbroadcasting/get_list_draft/', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@get_list_draft')->name('powerpanel.fmbroadcasting.get_list_draft');
    Route::post('powerpanel/fmbroadcasting/get_list_trash/', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@get_list_trash')->name('powerpanel.fmbroadcasting.get_list_trash');
    Route::post('powerpanel/fmbroadcasting/get_list_favorite/', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@get_list_favorite')->name('powerpanel.fmbroadcasting.get_list_favorite');

    Route::get('powerpanel/fmbroadcasting/add/', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@edit')->name('powerpanel.fmbroadcasting.add');
    Route::post('powerpanel/fmbroadcasting/add/', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@handlePost')->name('powerpanel.fmbroadcasting.add');

    Route::get('/powerpanel/fmbroadcasting/{alias}/edit', ['as' => 'powerpanel.fmbroadcasting.edit', 'uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@edit', 'middleware' => 'permission:fmbroadcasting-edit']);
    Route::post('/powerpanel/fmbroadcasting/{alias}/edit', ['as' => 'powerpanel.fmbroadcasting.handleEditPost', 'uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@handlePost', 'middleware' => 'permission:fmbroadcasting-edit']);

    Route::post('powerpanel/fmbroadcasting/DeleteRecord', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@DeleteRecord');
    Route::post('powerpanel/fmbroadcasting/publish', ['uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@publish', 'middleware' => 'permission:fmbroadcasting-edit']);
    Route::post('powerpanel/fmbroadcasting/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@reorder', 'middleware' => 'permission:fmbroadcasting-list']);
    Route::post('powerpanel/fmbroadcasting/addpreview', ['as' => 'powerpanel.fmbroadcasting.addpreview', 'uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@addPreview', 'middleware' => 'permission:fmbroadcasting-create']);             
    Route::post('powerpanel/fmbroadcasting/getChildData', ['uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@getChildData', 'middleware' => 'permission:fmbroadcasting-list']);
    Route::post('powerpanel/fmbroadcasting/ApprovedData_Listing', ['uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@ApprovedData_Listing', 'middleware' => 'permission:fmbroadcasting-list']);
    Route::post('powerpanel/fmbroadcasting/getChildData_rollback', ['uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@getChildData_rollback']);
    Route::post('powerpanel/fmbroadcasting/insertComents', ['uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@insertComents']);
    Route::post('powerpanel/fmbroadcasting/Get_Comments', ['uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@Get_Comments']);
    Route::post('powerpanel/fmbroadcasting/rollback-record', ['uses' => 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@rollBackRecord', 'middleware' => 'permission:fmbroadcasting-list']);
    Route::post('powerpanel/fmbroadcasting/get_builder_list', 'Powerpanel\FMBroadcasting\Controllers\Powerpanel\FMBroadcastingController@get_buider_list');
    
});
