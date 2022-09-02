<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/photo-gallery/', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@index')->name('powerpanel.photo-gallery.list');
    Route::get('powerpanel/photo-gallery/', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@index')->name('powerpanel.photo-gallery.index');
    
    Route::post('powerpanel/photo-gallery/get_list/', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@get_list')->name('powerpanel.photo-gallery.get_list');
    Route::post('powerpanel/photo-gallery/get_list_New/', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@get_list_New')->name('powerpanel.photo-gallery.get_list_New');
    Route::post('powerpanel/photo-gallery/get_list_draft/', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@get_list_draft')->name('powerpanel.photo-gallery.get_list_draft');
    Route::post('powerpanel/photo-gallery/get_list_trash/', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@get_list_trash')->name('powerpanel.photo-gallery.get_list_trash');
    Route::post('powerpanel/photo-gallery/get_list_favorite/', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@get_list_favorite')->name('powerpanel.photo-gallery.get_list_favorite');

    Route::get('powerpanel/photo-gallery/add/', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@edit')->name('powerpanel.photo-gallery.add');
    Route::post('powerpanel/photo-gallery/add/', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@handlePost')->name('powerpanel.photo-gallery.add');

    Route::get('/powerpanel/photo-gallery/{alias}/edit', ['as' => 'powerpanel.photo-gallery.edit', 'uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@edit', 'middleware' => 'permission:photo-gallery-edit']);
    Route::post('/powerpanel/photo-gallery/{alias}/edit', ['as' => 'powerpanel.photo-gallery.handleEditPost', 'uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@handlePost', 'middleware' => 'permission:photo-gallery-edit']);

    Route::post('powerpanel/photo-gallery/DeleteRecord', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@DeleteRecord');
    Route::post('powerpanel/photo-gallery/publish', ['uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@publish', 'middleware' => 'permission:photo-gallery-edit']);
    Route::post('powerpanel/photo-gallery/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@reorder', 'middleware' => 'permission:photo-gallery-list']);
       
    Route::post('powerpanel/photo-gallery/getChildData', ['uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@getChildData', 'middleware' => 'permission:photo-gallery-list']);
    Route::post('powerpanel/photo-gallery/ApprovedData_Listing', ['uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@ApprovedData_Listing', 'middleware' => 'permission:photo-gallery-list']);
    Route::post('powerpanel/photo-gallery/getChildData_rollback', ['uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@getChildData_rollback']);
    Route::post('powerpanel/photo-gallery/insertComents', ['uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@insertComents']);
    Route::post('powerpanel/photo-gallery/Get_Comments', ['uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@Get_Comments']);
    Route::post('powerpanel/photo-gallery/get_builder_list', 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@get_buider_list');

    Route::post('/powerpanel/photo-gallery/update', ['as' => '/powerpanel/photo-gallery/update', 'uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@store', 'middleware' => ['permission:photo-gallery-edit']]);
    Route::post('/powerpanel/photo-gallery/update_status', ['as' => '/powerpanel/photo-gallery/update_status', 'uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@update_status', 'middleware' => ['permission:photo-gallery-edit']]);
    Route::post('/powerpanel/photo-gallery/destroy', ['as' => '/powerpanel/photo-gallery/destroy', 'uses' => 'Powerpanel\PhotoGallery\Controllers\Powerpanel\PhotoGalleryController@destroy', 'middleware' => ['permission:photo-gallery-delete']]);

});
