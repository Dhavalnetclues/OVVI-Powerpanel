<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/news-category/', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@index')->name('powerpanel.news-category.list');
    Route::get('powerpanel/news-category/', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@index')->name('powerpanel.news-category.index');
    
    Route::post('powerpanel/news-category/get_list/', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@get_list')->name('powerpanel.news-category.get_list');
    Route::post('powerpanel/news-category/get_list_New/', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@get_list_New')->name('powerpanel.news-category.get_list_New');
    Route::post('powerpanel/news-category/get_list_draft/', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@get_list_draft')->name('powerpanel.news-category.get_list_draft');
    Route::post('powerpanel/news-category/get_list_trash/', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@get_list_trash')->name('powerpanel.news-category.get_list_trash');
    Route::post('powerpanel/news-category/get_list_favorite/', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@get_list_favorite')->name('powerpanel.news-category.get_list_favorite');

    Route::get('powerpanel/news-category/add/', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@edit')->name('powerpanel.news-category.add');
    Route::post('powerpanel/news-category/add/', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@handlePost')->name('powerpanel.news-category.add');

    Route::get('/powerpanel/news-category/{alias}/edit', ['as' => 'powerpanel.news-category.edit', 'uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@edit', 'middleware' => 'permission:news-category-edit']);
    Route::post('/powerpanel/news-category/{alias}/edit', ['as' => 'powerpanel.news-category.handleEditPost', 'uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@handlePost', 'middleware' => 'permission:news-category-edit']);

    Route::post('powerpanel/news-category/DeleteRecord', 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@DeleteRecord');
    Route::post('powerpanel/news-category/publish', ['uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@publish', 'middleware' => 'permission:news-category-edit']);
    Route::post('powerpanel/news-category/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@reorder', 'middleware' => 'permission:news-category-list']);
    Route::post('powerpanel/news-category/addpreview', ['as' => 'powerpanel.news-category.addpreview', 'uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@addPreview', 'middleware' => 'permission:news-category-create']);   
    Route::post('powerpanel/news-category/getChildData', ['uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@getChildData', 'middleware' => 'permission:news-category-list']);
    Route::post('powerpanel/news-category/ApprovedData_Listing', ['uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@ApprovedData_Listing', 'middleware' => 'permission:news-category-list']);
    Route::post('powerpanel/news-category/getChildData_rollback', ['uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@getChildData_rollback']);
    Route::post('powerpanel/news-category/insertComents', ['uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@insertComents']);
    Route::post('powerpanel/news-category/Get_Comments', ['uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@Get_Comments']);
    Route::post('powerpanel/news-category/get_builder_list', ['uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@get_builder_list']);
    Route::post('powerpanel/news-category/getAllCategory', ['uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@getAllCategory']);

    Route::post('powerpanel/news-category/rollback-record', ['uses' => 'Powerpanel\NewsCategory\Controllers\Powerpanel\NewsCategoryController@rollBackRecord', 'middleware' => 'permission:news-category-list']);
});
