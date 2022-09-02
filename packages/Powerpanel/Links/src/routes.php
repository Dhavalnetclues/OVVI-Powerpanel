<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/links/', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@index')->name('powerpanel.links.list');
    Route::get('powerpanel/links/', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@index')->name('powerpanel.links.index');
    
    Route::post('powerpanel/links/get_list/', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@get_list')->name('powerpanel.links.get_list');
    Route::post('powerpanel/links/get_list_New/', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@get_list_New')->name('powerpanel.links.get_list_New');
    Route::post('powerpanel/links/get_list_draft/', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@get_list_draft')->name('powerpanel.links.get_list_draft');
    Route::post('powerpanel/links/get_list_trash/', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@get_list_trash')->name('powerpanel.links.get_list_trash');
    Route::post('powerpanel/links/get_list_favorite/', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@get_list_favorite')->name('powerpanel.links.get_list_favorite');

    Route::get('powerpanel/links/add/', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@edit')->name('powerpanel.links.add');
    Route::post('powerpanel/links/add/', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@handlePost')->name('powerpanel.links.add');

    Route::get('/powerpanel/links/{alias}/edit', ['as' => 'powerpanel.links.edit', 'uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@edit', 'middleware' => 'permission:links-edit']);
    Route::post('/powerpanel/links/{alias}/edit', ['as' => 'powerpanel.links.handleEditPost', 'uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@handlePost', 'middleware' => 'permission:links-edit']);

    Route::post('powerpanel/links/DeleteRecord', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@DeleteRecord');
    Route::post('powerpanel/links/publish', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@publish', 'middleware' => 'permission:links-edit']);
    Route::post('powerpanel/links/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@reorder', 'middleware' => 'permission:links-list']);
       
    Route::post('powerpanel/links/getChildData', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@getChildData', 'middleware' => 'permission:links-list']);
    Route::post('powerpanel/links/ApprovedData_Listing', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@ApprovedData_Listing', 'middleware' => 'permission:links-list']);
    Route::post('powerpanel/links/getChildData_rollback', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@getChildData_rollback']);
    Route::post('powerpanel/links/insertComents', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@insertComents']);
    Route::post('powerpanel/links/Get_Comments', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@Get_Comments']);
    Route::post('powerpanel/links/get_builder_list', 'Powerpanel\Links\Controllers\Powerpanel\LinksController@get_buider_list');
    Route::post('powerpanel/links/selectRecords', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@selectRecords']);
    Route::post('powerpanel/links/getCategory', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@getCategory']);
    Route::post('powerpanel/links/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@getSectorwiseCategoryGrid']);
    Route::post('powerpanel/links/rollback-record', ['uses' => 'Powerpanel\Links\Controllers\Powerpanel\LinksController@rollBackRecord', 'middleware' => 'permission:links-list']);
});
