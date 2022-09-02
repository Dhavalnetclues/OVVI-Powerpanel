<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/public-record-category/', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@index')->name('powerpanel.public-record-category.list');
    Route::get('powerpanel/public-record-category/', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@index')->name('powerpanel.public-record-category.index');
    
    Route::post('powerpanel/public-record-category/get_list/', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@get_list')->name('powerpanel.public-record-category.get_list');
    Route::post('powerpanel/public-record-category/get_list_New/', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@get_list_New')->name('powerpanel.public-record-category.get_list_New');
    Route::post('powerpanel/public-record-category/get_list_draft/', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@get_list_draft')->name('powerpanel.public-record-category.get_list_draft');
    Route::post('powerpanel/public-record-category/get_list_trash/', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@get_list_trash')->name('powerpanel.public-record-category.get_list_trash');
    Route::post('powerpanel/public-record-category/get_list_favorite/', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@get_list_favorite')->name('powerpanel.public-record-category.get_list_favorite');

    Route::get('powerpanel/public-record-category/add/', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@edit')->name('powerpanel.public-record-category.add');
    Route::post('powerpanel/public-record-category/add/', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@handlePost')->name('powerpanel.public-record-category.add');

    Route::get('/powerpanel/public-record-category/{alias}/edit', ['as' => 'powerpanel.public-record-category.edit', 'uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@edit', 'middleware' => 'permission:public-record-category-edit']);
    Route::post('/powerpanel/public-record-category/{alias}/edit', ['as' => 'powerpanel.public-record-category.handleEditPost', 'uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@handlePost', 'middleware' => 'permission:public-record-category-edit']);

    Route::post('powerpanel/public-record-category/DeleteRecord', 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@DeleteRecord');
    Route::post('powerpanel/public-record-category/publish', ['uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@publish', 'middleware' => 'permission:public-record-category-edit']);
    Route::post('powerpanel/public-record-category/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@reorder', 'middleware' => 'permission:public-record-category-list']);
    Route::post('powerpanel/public-record-category/addpreview', ['as' => 'powerpanel.public-record-category.addpreview', 'uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@addPreview', 'middleware' => 'permission:public-record-category-create']);   
    Route::post('powerpanel/public-record-category/getChildData', ['uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@getChildData', 'middleware' => 'permission:public-record-category-list']);
    Route::post('powerpanel/public-record-category/ApprovedData_Listing', ['uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@ApprovedData_Listing', 'middleware' => 'permission:public-record-category-list']);
    Route::post('powerpanel/public-record-category/getChildData_rollback', ['uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@getChildData_rollback']);
    Route::post('powerpanel/public-record-category/insertComents', ['uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@insertComents']);
    Route::post('powerpanel/public-record-category/Get_Comments', ['uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@Get_Comments']);
    Route::post('powerpanel/public-record-category/get_builder_list', ['uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@get_builder_list']);
    Route::post('powerpanel/public-record-category/getAllCategory', ['uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@getAllCategory']);

    Route::post('powerpanel/public-record-category/rollback-record', ['uses' => 'Powerpanel\PublicRecordCategory\Controllers\Powerpanel\PublicRecordCategoryController@rollBackRecord', 'middleware' => 'permission:public-record-category-list']);
});

Route::get('uploadPublicRecordCategoryCSV', 'Powerpanel\PublicRecordCategory\Controllers\PublicRecordCategoryCSVController@uploadCSV');
