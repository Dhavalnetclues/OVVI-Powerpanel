<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/publications-category/', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@index')->name('powerpanel.publications-category.list');
    Route::get('powerpanel/publications-category/', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@index')->name('powerpanel.publications-category.index');
    
    Route::post('powerpanel/publications-category/get_list/', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@get_list')->name('powerpanel.publications-category.get_list');
    Route::post('powerpanel/publications-category/get_list_New/', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@get_list_New')->name('powerpanel.publications-category.get_list_New');
    Route::post('powerpanel/publications-category/get_list_draft/', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@get_list_draft')->name('powerpanel.publications-category.get_list_draft');
    Route::post('powerpanel/publications-category/get_list_trash/', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@get_list_trash')->name('powerpanel.publications-category.get_list_trash');
    Route::post('powerpanel/publications-category/get_list_favorite/', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@get_list_favorite')->name('powerpanel.publications-category.get_list_favorite');

    Route::get('powerpanel/publications-category/add/', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@edit')->name('powerpanel.publications-category.add');
    Route::post('powerpanel/publications-category/add/', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@handlePost')->name('powerpanel.publications-category.add');

    Route::get('/powerpanel/publications-category/{alias}/edit', ['as' => 'powerpanel.publications-category.edit', 'uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@edit', 'middleware' => 'permission:publications-category-edit']);
    Route::post('/powerpanel/publications-category/{alias}/edit', ['as' => 'powerpanel.publications-category.handleEditPost', 'uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@handlePost', 'middleware' => 'permission:publications-category-edit']);

    Route::post('powerpanel/publications-category/DeleteRecord', 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@DeleteRecord');
    Route::post('powerpanel/publications-category/publish', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@publish', 'middleware' => 'permission:publications-category-edit']);
    Route::post('powerpanel/publications-category/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@reorder', 'middleware' => 'permission:publications-category-list']);
    Route::post('powerpanel/publications-category/addpreview', ['as' => 'powerpanel.publications-category.addpreview', 'uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@addPreview', 'middleware' => 'permission:publications-category-create']);       
    Route::post('powerpanel/publications-category/getChildData', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@getChildData', 'middleware' => 'permission:publications-category-list']);
    Route::post('powerpanel/publications-category/ApprovedData_Listing', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@ApprovedData_Listing', 'middleware' => 'permission:publications-category-list']);
    Route::post('powerpanel/publications-category/getChildData_rollback', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@getChildData_rollback']);
    Route::post('powerpanel/publications-category/insertComents', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@insertComents']);
    Route::post('powerpanel/publications-category/Get_Comments', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@Get_Comments']);
    
     Route::post('powerpanel/publications-category/get_builder_list', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@get_builder_list']);
    Route::post('powerpanel/publications-category/getAllCategory', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@getAllCategory']);
    Route::post('powerpanel/publications-category/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@getSectorwiseCategoryGrid']);

    Route::post('powerpanel/publications-category/rollback-record', ['uses' => 'Powerpanel\PublicationsCategory\Controllers\Powerpanel\PublicationsCategoryController@rollBackRecord', 'middleware' => 'permission:publications-category-list']);
});

Route::get('uploadPublicationsCategoryCSV', 'Powerpanel\PublicationsCategory\Controllers\PublicationsCategoryCSVController@uploadCSV');
