<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/publications/', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@index')->name('powerpanel.publications.list');
    Route::get('powerpanel/publications/', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@index')->name('powerpanel.publications.index');
    
    Route::post('powerpanel/publications/get_list/', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@get_list')->name('powerpanel.publications.get_list');
    Route::post('powerpanel/publications/get_list_New/', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@get_list_New')->name('powerpanel.publications.get_list_New');
    Route::post('powerpanel/publications/get_list_draft/', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@get_list_draft')->name('powerpanel.publications.get_list_draft');
    Route::post('powerpanel/publications/get_list_trash/', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@get_list_trash')->name('powerpanel.publications.get_list_trash');
    Route::post('powerpanel/publications/get_list_favorite/', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@get_list_favorite')->name('powerpanel.publications.get_list_favorite');

    Route::get('powerpanel/publications/add/', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@edit')->name('powerpanel.publications.add');
    Route::post('powerpanel/publications/add/', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@handlePost')->name('powerpanel.publications.add');

    Route::get('/powerpanel/publications/{alias}/edit', ['as' => 'powerpanel.publications.edit', 'uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@edit', 'middleware' => 'permission:publications-edit']);
    Route::post('/powerpanel/publications/{alias}/edit', ['as' => 'powerpanel.publications.handleEditPost', 'uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@handlePost', 'middleware' => 'permission:publications-edit']);

    Route::post('powerpanel/publications/DeleteRecord', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@DeleteRecord');
    Route::post('powerpanel/publications/publish', ['uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@publish', 'middleware' => 'permission:publications-edit']);
    Route::post('powerpanel/publications/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@reorder', 'middleware' => 'permission:publications-list']);
    Route::post('powerpanel/publications/addpreview', ['as' => 'powerpanel.publications.addpreview', 'uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@addPreview', 'middleware' => 'permission:publications-create']);    
    Route::post('powerpanel/publications/getChildData', ['uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@getChildData', 'middleware' => 'permission:publications-list']);
    Route::post('powerpanel/publications/ApprovedData_Listing', ['uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@ApprovedData_Listing', 'middleware' => 'permission:publications-list']);
    Route::post('powerpanel/publications/getChildData_rollback', ['uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@getChildData_rollback']);
     Route::post('powerpanel/publications/insertComents', ['uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@insertComents']);
    Route::post('powerpanel/publications/Get_Comments', ['uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@Get_Comments']);
     Route::post('powerpanel/publications/getCategory', ['uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@getCategory']);
     Route::post('powerpanel/publications/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@getSectorwiseCategoryGrid']);
    Route::post('powerpanel/publications/get_builder_list', 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@get_buider_list');

    Route::post('powerpanel/publications/rollback-record', ['uses' => 'Powerpanel\Publications\Controllers\Powerpanel\PublicationsController@rollBackRecord', 'middleware' => 'permission:publications-list']);
});

Route::get('uploadPublicationsCSV', 'Powerpanel\Publications\Controllers\PublicationsCSVController@uploadCSV');