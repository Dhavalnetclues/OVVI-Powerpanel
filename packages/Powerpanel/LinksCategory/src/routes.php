<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/links-category/', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@index')->name('powerpanel.links-category.list');
    Route::get('powerpanel/links-category/', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@index')->name('powerpanel.links-category.index');
    
    Route::post('powerpanel/links-category/get_list/', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@get_list')->name('powerpanel.links-category.get_list');
    Route::post('powerpanel/links-category/get_list_New/', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@get_list_New')->name('powerpanel.links-category.get_list_New');
    Route::post('powerpanel/links-category/get_list_draft/', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@get_list_draft')->name('powerpanel.links-category.get_list_draft');
    Route::post('powerpanel/links-category/get_list_trash/', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@get_list_trash')->name('powerpanel.links-category.get_list_trash');
    Route::post('powerpanel/links-category/get_list_favorite/', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@get_list_favorite')->name('powerpanel.links-category.get_list_favorite');

    Route::get('powerpanel/links-category/add/', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@edit')->name('powerpanel.links-category.add');
    Route::post('powerpanel/links-category/add/', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@handlePost')->name('powerpanel.links-category.add');

    Route::get('/powerpanel/links-category/{alias}/edit', ['as' => 'powerpanel.links-category.edit', 'uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@edit', 'middleware' => 'permission:links-category-edit']);
    Route::post('/powerpanel/links-category/{alias}/edit', ['as' => 'powerpanel.links-category.handleEditPost', 'uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@handlePost', 'middleware' => 'permission:links-category-edit']);

    Route::post('powerpanel/links-category/DeleteRecord', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@DeleteRecord');
    Route::post('powerpanel/links-category/publish', ['uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@publish', 'middleware' => 'permission:links-category-edit']);
    Route::post('powerpanel/links-category/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@reorder', 'middleware' => 'permission:links-category-list']);
       
    Route::post('powerpanel/links-category/getChildData', ['uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@getChildData', 'middleware' => 'permission:links-category-list']);
    Route::post('powerpanel/links-category/ApprovedData_Listing', ['uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@ApprovedData_Listing', 'middleware' => 'permission:links-category-list']);
    Route::post('powerpanel/links-category/getChildData_rollback', ['uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@getChildData_rollback']);
    Route::post('powerpanel/links-category/insertComents', ['uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@insertComents']);
    Route::post('powerpanel/links-category/Get_Comments', ['uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@Get_Comments']);
    Route::post('powerpanel/links-category/getAllCategory', 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@getAllCategory');
    Route::post('powerpanel/links-category/get_builder_list', ['uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@get_builder_list']);

    Route::post('powerpanel/links-category/rollback-record', ['uses' => 'Powerpanel\LinksCategory\Controllers\Powerpanel\LinksCategoryController@rollBackRecord', 'middleware' => 'permission:links-category-list']);
});
