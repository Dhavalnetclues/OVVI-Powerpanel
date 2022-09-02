<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/video-gallery/', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@index')->name('powerpanel.video-gallery.list');
    Route::get('powerpanel/video-gallery/', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@index')->name('powerpanel.video-gallery.index');
    
    Route::post('powerpanel/video-gallery/get_list/', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@get_list')->name('powerpanel.video-gallery.get_list');
    Route::post('powerpanel/video-gallery/get_list_New/', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@get_list_New')->name('powerpanel.video-gallery.get_list_New');
    Route::post('powerpanel/video-gallery/get_list_draft/', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@get_list_draft')->name('powerpanel.video-gallery.get_list_draft');
    Route::post('powerpanel/video-gallery/get_list_trash/', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@get_list_trash')->name('powerpanel.video-gallery.get_list_trash');
    Route::post('powerpanel/video-gallery/get_list_favorite/', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@get_list_favorite')->name('powerpanel.video-gallery.get_list_favorite');

    Route::get('powerpanel/video-gallery/add/', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@edit')->name('powerpanel.video-gallery.add');
    Route::post('powerpanel/video-gallery/add/', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@handlePost')->name('powerpanel.video-gallery.add');

    Route::get('/powerpanel/video-gallery/{alias}/edit', ['as' => 'powerpanel.video-gallery.edit', 'uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@edit', 'middleware' => 'permission:video-gallery-edit']);
    Route::post('/powerpanel/video-gallery/{alias}/edit', ['as' => 'powerpanel.video-gallery.handleEditPost', 'uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@handlePost', 'middleware' => 'permission:video-gallery-edit']);

    Route::post('powerpanel/video-gallery/DeleteRecord', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@DeleteRecord');
    Route::post('powerpanel/video-gallery/publish', ['uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@publish', 'middleware' => 'permission:video-gallery-edit']);
    Route::post('powerpanel/video-gallery/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@reorder', 'middleware' => 'permission:video-gallery-list']);
       
    Route::post('powerpanel/video-gallery/getChildData', ['uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@getChildData', 'middleware' => 'permission:video-gallery-list']);
    Route::post('powerpanel/video-gallery/ApprovedData_Listing', ['uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@ApprovedData_Listing', 'middleware' => 'permission:video-gallery-list']);
    Route::post('powerpanel/video-gallery/getChildData_rollback', ['uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@getChildData_rollback']);
    Route::post('powerpanel/video-gallery/insertComents', ['uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@insertComents']);
    Route::post('powerpanel/video-gallery/Get_Comments', ['uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@Get_Comments']);
    Route::post('powerpanel/video-gallery/get_builder_list', 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@get_buider_list');

    Route::post('/powerpanel/video-gallery/update', ['as' => '/powerpanel/video-gallery/update', 'uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@store', 'middleware' => ['permission:video-gallery-edit']]);
    Route::post('/powerpanel/video-gallery/update_status', ['as' => '/powerpanel/video-gallery/update_status', 'uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@update_status', 'middleware' => ['permission:video-gallery-edit']]);
    Route::post('/powerpanel/video-gallery/destroy', ['as' => '/powerpanel/video-gallery/destroy', 'uses' => 'Powerpanel\VideoGallery\Controllers\Powerpanel\VideoGalleryController@destroy', 'middleware' => ['permission:video-gallery-delete']]);

});
