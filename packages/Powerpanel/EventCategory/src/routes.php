<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/event-category/', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@index')->name('powerpanel.event-category.list');
    Route::get('powerpanel/event-category/', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@index')->name('powerpanel.event-category.index');
    
    Route::post('powerpanel/event-category/get_list/', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@get_list')->name('powerpanel.event-category.get_list');
    Route::post('powerpanel/event-category/get_list_New/', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@get_list_New')->name('powerpanel.event-category.get_list_New');
    Route::post('powerpanel/event-category/get_list_draft/', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@get_list_draft')->name('powerpanel.event-category.get_list_draft');
    Route::post('powerpanel/event-category/get_list_trash/', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@get_list_trash')->name('powerpanel.event-category.get_list_trash');
    Route::post('powerpanel/event-category/get_list_favorite/', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@get_list_favorite')->name('powerpanel.event-category.get_list_favorite');

    Route::get('powerpanel/event-category/add/', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@edit')->name('powerpanel.event-category.add');
    Route::post('powerpanel/event-category/add/', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@handlePost')->name('powerpanel.event-category.add');

    Route::get('/powerpanel/event-category/{alias}/edit', ['as' => 'powerpanel.event-category.edit', 'uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@edit', 'middleware' => 'permission:event-category-edit']);
    Route::post('/powerpanel/event-category/{alias}/edit', ['as' => 'powerpanel.event-category.handleEditPost', 'uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@handlePost', 'middleware' => 'permission:event-category-edit']);

    Route::post('powerpanel/event-category/DeleteRecord', 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@DeleteRecord');
    Route::post('powerpanel/event-category/publish', ['uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@publish', 'middleware' => 'permission:event-category-edit']);
    Route::post('powerpanel/event-category/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@reorder', 'middleware' => 'permission:event-category-list']);
    Route::post('powerpanel/event-category/addpreview', ['as' => 'powerpanel.event-category.addpreview', 'uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@addPreview', 'middleware' => 'permission:event-category-create']);   
    Route::post('powerpanel/event-category/getChildData', ['uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@getChildData', 'middleware' => 'permission:event-category-list']);
    Route::post('powerpanel/event-category/ApprovedData_Listing', ['uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@ApprovedData_Listing', 'middleware' => 'permission:event-category-list']);
    Route::post('powerpanel/event-category/getChildData_rollback', ['uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@getChildData_rollback']);
    Route::post('powerpanel/event-category/insertComents', ['uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@insertComents']);
    Route::post('powerpanel/event-category/Get_Comments', ['uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@Get_Comments']);
    Route::post('powerpanel/event-category/get_builder_list', ['uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@get_builder_list']);
    Route::post('powerpanel/event-category/getAllCategory', ['uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@getAllCategory']);
    Route::post('powerpanel/event-category/rollback-record', ['uses' => 'Powerpanel\EventCategory\Controllers\Powerpanel\EventCategoryController@rollBackRecord', 'middleware' => 'permission:event-category-list']);
});
