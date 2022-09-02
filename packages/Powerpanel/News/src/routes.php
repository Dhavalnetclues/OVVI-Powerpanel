<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/news/', 'Powerpanel\News\Controllers\Powerpanel\NewsController@index')->name('powerpanel.news.list');
    Route::get('powerpanel/news/', 'Powerpanel\News\Controllers\Powerpanel\NewsController@index')->name('powerpanel.news.index');
    
    Route::post('powerpanel/news/get_list/', 'Powerpanel\News\Controllers\Powerpanel\NewsController@get_list')->name('powerpanel.news.get_list');
    Route::post('powerpanel/news/get_list_New/', 'Powerpanel\News\Controllers\Powerpanel\NewsController@get_list_New')->name('powerpanel.news.get_list_New');
    Route::post('powerpanel/news/get_list_draft/', 'Powerpanel\News\Controllers\Powerpanel\NewsController@get_list_draft')->name('powerpanel.news.get_list_draft');
    Route::post('powerpanel/news/get_list_trash/', 'Powerpanel\News\Controllers\Powerpanel\NewsController@get_list_trash')->name('powerpanel.news.get_list_trash');
    Route::post('powerpanel/news/get_list_favorite/', 'Powerpanel\News\Controllers\Powerpanel\NewsController@get_list_favorite')->name('powerpanel.news.get_list_favorite');

    Route::get('powerpanel/news/add/', 'Powerpanel\News\Controllers\Powerpanel\NewsController@edit')->name('powerpanel.news.add');
    Route::post('powerpanel/news/add/', 'Powerpanel\News\Controllers\Powerpanel\NewsController@handlePost')->name('powerpanel.news.add');

    Route::get('/powerpanel/news/{alias}/edit', ['as' => 'powerpanel.news.edit', 'uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@edit', 'middleware' => 'permission:news-edit']);
    Route::post('/powerpanel/news/{alias}/edit', ['as' => 'powerpanel.news.handleEditPost', 'uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@handlePost', 'middleware' => 'permission:news-edit']);

    Route::post('powerpanel/news/DeleteRecord', 'Powerpanel\News\Controllers\Powerpanel\NewsController@DeleteRecord');
    Route::post('powerpanel/news/publish', ['uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@publish', 'middleware' => 'permission:news-edit']);
    Route::post('powerpanel/news/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@reorder', 'middleware' => 'permission:news-list']);
    Route::post('powerpanel/news/addpreview', ['as' => 'powerpanel.news.addpreview', 'uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@addPreview', 'middleware' => 'permission:news-create']);       
    Route::post('powerpanel/news/getChildData', ['uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@getChildData', 'middleware' => 'permission:news-list']);
    Route::post('powerpanel/news/ApprovedData_Listing', ['uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@ApprovedData_Listing', 'middleware' => 'permission:news-list']);
    Route::post('powerpanel/news/getChildData_rollback', ['uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@getChildData_rollback']);
    Route::post('powerpanel/news/insertComents', ['uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@insertComents']);
    Route::post('powerpanel/news/Get_Comments', ['uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@Get_Comments']);
    Route::post('powerpanel/news/get_builder_list', 'Powerpanel\News\Controllers\Powerpanel\NewsController@get_buider_list');

    Route::post('powerpanel/news/rollback-record', ['uses' => 'Powerpanel\News\Controllers\Powerpanel\NewsController@rollBackRecord', 'middleware' => 'permission:news-list']);
});
