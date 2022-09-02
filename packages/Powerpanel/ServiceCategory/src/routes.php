<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/service-category', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@index')->name('powerpanel.service-category.index');
    Route::post('powerpanel/service-category/get_list/', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@get_list');
    Route::post('powerpanel/service-category/get_list_New/', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@get_list_New');
    Route::post('powerpanel/service-category/get_list_favorite/', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@get_list_favorite');
    Route::post('powerpanel/service-category/get_list_draft/', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@get_list_draft');
    Route::post('powerpanel/service-category/get_list_trash/', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@get_list_trash');

    Route::post('powerpanel/service-category/publish', ['uses' => 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@publish', 'middleware' => 'permission:' . Config::get('Constant.MODULE.NAME') . '-edit']);

    Route::post('powerpanel/service-category/reorder/', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@reorder')->name('powerpanel.service-category.reorder');

    Route::post('powerpanel/service-category/addpreview/', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@addPreview')->name('powerpanel.service-category.addpreview');

    Route::get('powerpanel/service-category/add', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@edit')->name('powerpanel.service-category.add');
    Route::post('powerpanel/service-category/add/', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@handlePost')->name('powerpanel.service-category.handleAddPost');
    
    Route::get('powerpanel/service-category/{alias}/edit', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@edit')->name('powerpanel.service-category.edit');
    Route::post('powerpanel/service-category/{alias}/edit', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@handlePost')->name('powerpanel/service-category/handleEditPost');

    Route::post('powerpanel/service-category/DeleteRecord', 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@DeleteRecord');

    Route::post('powerpanel/service-category/getChildData', ['uses' => 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@getChildData', 'middleware' => 'permission:service-category-list']);
    Route::post('powerpanel/service-category/ApprovedData_Listing', ['uses' => 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@ApprovedData_Listing', 'middleware' => 'permission:service-category-list']);
    Route::post('powerpanel/service-category/getChildData_rollback', ['uses' => 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@getChildData_rollback']);

    Route::post('powerpanel/service-category/rollback-record', ['uses' => 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@rollBackRecord', 'middleware' => 'permission:service-category-list']);
    
    Route::post('powerpanel/service-category/Get_Comments', ['uses' => 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@Get_Comments']);
    Route::post('powerpanel/service-category/get_builder_list', ['uses' => 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@get_builder_list']);
    Route::post('powerpanel/service-category/getAllCategory', ['uses' => 'Powerpanel\ServiceCategory\Controllers\powerpanel\ServiceCategoryController@getAllCategory']);
});
