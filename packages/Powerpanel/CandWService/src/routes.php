<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/candwservice/', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@index')->name('powerpanel.candwservice.list');
    Route::get('powerpanel/candwservice/', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@index')->name('powerpanel.candwservice.index');
    
    Route::post('powerpanel/candwservice/get_list/', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@get_list')->name('powerpanel.candwservice.get_list');
    Route::post('powerpanel/candwservice/get_list_New/', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@get_list_New')->name('powerpanel.candwservice.get_list_New');
    Route::post('powerpanel/candwservice/get_list_draft/', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@get_list_draft')->name('powerpanel.candwservice.get_list_draft');
    Route::post('powerpanel/candwservice/get_list_trash/', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@get_list_trash')->name('powerpanel.candwservice.get_list_trash');
    Route::post('powerpanel/candwservice/get_list_favorite/', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@get_list_favorite')->name('powerpanel.candwservice.get_list_favorite');

    Route::get('powerpanel/candwservice/add/', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@edit')->name('powerpanel.candwservice.add');
    Route::post('powerpanel/candwservice/add/', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@handlePost')->name('powerpanel.candwservice.add');

    Route::get('/powerpanel/candwservice/{alias}/edit', ['as' => 'powerpanel.candwservice.edit', 'uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@edit', 'middleware' => 'permission:candwservice-edit']);
    Route::post('/powerpanel/candwservice/{alias}/edit', ['as' => 'powerpanel.candwservice.handleEditPost', 'uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@handlePost', 'middleware' => 'permission:candwservice-edit']);

    Route::post('powerpanel/candwservice/DeleteRecord', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@DeleteRecord');
    Route::post('powerpanel/candwservice/publish', ['uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@publish', 'middleware' => 'permission:candwservice-edit']);
    Route::post('powerpanel/candwservice/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@reorder', 'middleware' => 'permission:candwservice-list']);
    Route::post('powerpanel/candwservice/addpreview', ['as' => 'powerpanel.candwservice.addpreview', 'uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@addPreview', 'middleware' => 'permission:candwservice-create']);       
    Route::post('powerpanel/candwservice/getChildData', ['uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@getChildData', 'middleware' => 'permission:candwservice-list']);
    Route::post('powerpanel/candwservice/ApprovedData_Listing', ['uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@ApprovedData_Listing', 'middleware' => 'permission:candwservice-list']);
    Route::post('powerpanel/candwservice/getChildData_rollback', ['uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@getChildData_rollback']);
    Route::post('powerpanel/candwservice/insertComents', ['uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@insertComents']);
    Route::post('powerpanel/candwservice/Get_Comments', ['uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@Get_Comments']);
    Route::post('powerpanel/candwservice/get_builder_list', 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@get_buider_list');

    Route::post('powerpanel/candwservice/rollback-record', ['uses' => 'Powerpanel\CandWService\Controllers\Powerpanel\CandWServiceController@rollBackRecord', 'middleware' => 'permission:candwservice-list']);
});
