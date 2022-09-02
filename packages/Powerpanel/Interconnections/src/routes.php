<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/interconnections/', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@index')->name('powerpanel.interconnections.list');
    Route::get('powerpanel/interconnections/', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@index')->name('powerpanel.interconnections.index');
    
    Route::post('powerpanel/interconnections/get_list/', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@get_list')->name('powerpanel.interconnections.get_list');
    Route::post('powerpanel/interconnections/get_list_New/', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@get_list_New')->name('powerpanel.interconnections.get_list_New');
    Route::post('powerpanel/interconnections/get_list_favorite/', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@get_list_favorite');
    Route::post('powerpanel/interconnections/get_list_draft/', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@get_list_draft');
    Route::post('powerpanel/interconnections/get_list_trash/', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@get_list_trash');
   
    Route::get('powerpanel/interconnections/add/', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@edit')->name('powerpanel.interconnections.add');
    Route::post('powerpanel/interconnections/add/', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@handlePost')->name('powerpanel.interconnections.add');

    Route::get('/powerpanel/interconnections/{alias}/edit', ['as' => 'powerpanel.interconnections.edit', 'uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@edit', 'middleware' => 'permission:interconnections-edit']);
    Route::post('/powerpanel/interconnections/{alias}/edit', ['as' => 'powerpanel.interconnections.handleEditPost', 'uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@handlePost', 'middleware' => 'permission:interconnections-edit']);

    Route::post('powerpanel/interconnections/DeleteRecord', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@DeleteRecord');
    Route::post('powerpanel/interconnections/publish', ['uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@publish', 'middleware' => 'permission:interconnections-edit']);
    Route::post('powerpanel/interconnections/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@reorder', 'middleware' => 'permission:interconnections-list']);
       
    Route::post('powerpanel/interconnections/getChildData', ['uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@getChildData', 'middleware' => 'permission:interconnections-list']);
    Route::post('powerpanel/interconnections/ApprovedData_Listing', ['uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@ApprovedData_Listing', 'middleware' => 'permission:interconnections-list']);
    Route::post('powerpanel/interconnections/getChildData_rollback', ['uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@getChildData_rollback']);
    Route::post('powerpanel/interconnections/insertComents', ['uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@insertComents']);
    Route::post('powerpanel/interconnections/Get_Comments', ['uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@Get_Comments']);

    Route::post('powerpanel/interconnections/rollback-record', ['uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@rollBackRecord', 'middleware' => 'permission:interconnections-list']);
    Route::post('powerpanel/interconnections/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@getSectorwiseCategoryGrid', 'middleware' => 'permission:interconnections-list']);
    
    Route::post('powerpanel/interconnections/get_builder_list', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@get_buider_list');
    Route::post('powerpanel/interconnections/getAllParents', 'Powerpanel\Interconnections\Controllers\Powerpanel\InterconnectionsController@getAllParents');
});
