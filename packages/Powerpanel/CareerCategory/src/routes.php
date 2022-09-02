<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/career-category', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@index')->name('powerpanel.career-category.index');
    Route::post('powerpanel/career-category/get_list/', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@get_list');
    Route::post('powerpanel/career-category/get_list_New/', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@get_list_New');
    Route::post('powerpanel/career-category/get_list_favorite/', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@get_list_favorite');
    Route::post('powerpanel/career-category/get_list_draft/', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@get_list_draft');
    Route::post('powerpanel/career-category/get_list_trash/', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@get_list_trash');

    Route::post('powerpanel/career-category/publish', ['uses' => 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@publish', 'middleware' => 'permission:' . Config::get('Constant.MODULE.NAME') . '-edit']);

    Route::post('powerpanel/career-category/reorder/', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@reorder')->name('powerpanel.career-category.reorder');

    Route::post('powerpanel/career-category/addpreview/', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@addPreview')->name('powerpanel.career-category.addpreview');

    Route::get('powerpanel/career-category/add', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@edit')->name('powerpanel.career-category.add');
    Route::post('powerpanel/career-category/add/', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@handlePost')->name('powerpanel.career-category.handleAddPost');
    
    Route::get('powerpanel/career-category/{alias}/edit', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@edit')->name('powerpanel.career-category.edit');
    Route::post('powerpanel/career-category/{alias}/edit', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@handlePost')->name('powerpanel/career-category/handleEditPost');

    Route::post('powerpanel/career-category/DeleteRecord', 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@DeleteRecord');

    Route::post('powerpanel/career-category/getChildData', ['uses' => 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@getChildData', 'middleware' => 'permission:career-category-list']);
    Route::post('powerpanel/career-category/ApprovedData_Listing', ['uses' => 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@ApprovedData_Listing', 'middleware' => 'permission:career-category-list']);
    Route::post('powerpanel/career-category/getChildData_rollback', ['uses' => 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@getChildData_rollback']);

    Route::post('powerpanel/career-category/rollback-record', ['uses' => 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@rollBackRecord', 'middleware' => 'permission:career-category-list']);
    
    Route::post('powerpanel/career-category/Get_Comments', ['uses' => 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@Get_Comments']);
    Route::post('powerpanel/career-category/get_builder_list', ['uses' => 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@get_builder_list']);
    Route::post('powerpanel/career-category/getAllCategory', ['uses' => 'Powerpanel\CareerCategory\Controllers\powerpanel\CareerCategoryController@getAllCategory']);
});
