<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/quick-links/', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@index')->name('powerpanel.quick-links.list');
    Route::get('powerpanel/quick-links/', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@index')->name('powerpanel.quick-links.index');
    
    Route::post('powerpanel/quick-links/get_list/', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@get_list')->name('powerpanel.quick-links.get_list');
    Route::post('powerpanel/quick-links/get_list_New/', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@get_list_New')->name('powerpanel.quick-links.get_list_New');
    Route::post('powerpanel/quick-links/get_list_draft/', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@get_list_draft')->name('powerpanel.quick-links.get_list_draft');
    Route::post('powerpanel/quick-links/get_list_trash/', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@get_list_trash')->name('powerpanel.quick-links.get_list_trash');
    Route::post('powerpanel/quick-links/get_list_favorite/', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@get_list_favorite')->name('powerpanel.quick-links.get_list_favorite');

    Route::get('powerpanel/quick-links/add/', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@edit')->name('powerpanel.quick-links.add');
    Route::post('powerpanel/quick-links/add/', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@handlePost')->name('powerpanel.quick-links.add');

    Route::get('/powerpanel/quick-links/{alias}/edit', ['as' => 'powerpanel.quick-links.edit', 'uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@edit', 'middleware' => 'permission:quick-links-edit']);
    Route::post('/powerpanel/quick-links/{alias}/edit', ['as' => 'powerpanel.quick-links.handleEditPost', 'uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@handlePost', 'middleware' => 'permission:quick-links-edit']);

    Route::post('powerpanel/quick-links/DeleteRecord', 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@DeleteRecord');
    Route::post('powerpanel/quick-links/publish', ['uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@publish', 'middleware' => 'permission:quick-links-edit']);
    Route::post('powerpanel/quick-links/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@reorder', 'middleware' => 'permission:quick-links-list']);
       
    Route::post('powerpanel/quick-links/getChildData', ['uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@getChildData', 'middleware' => 'permission:quick-links-list']);
    Route::post('powerpanel/quick-links/ApprovedData_Listing', ['uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@ApprovedData_Listing', 'middleware' => 'permission:quick-links-list']);
    Route::post('powerpanel/quick-links/getChildData_rollback', ['uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@getChildData_rollback']);
    Route::post('powerpanel/quick-links/insertComents', ['uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@insertComents']);
    Route::post('powerpanel/quick-links/Get_Comments', ['uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@Get_Comments']);
    
    Route::post('powerpanel/quick-links/selectRecords', ['uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@selectRecords']);

    Route::post('powerpanel/quick-links/rollback-record', ['uses' => 'Powerpanel\QuickLinks\Controllers\Powerpanel\QuickLinksController@rollBackRecord', 'middleware' => 'permission:quick-links-list']);
});
