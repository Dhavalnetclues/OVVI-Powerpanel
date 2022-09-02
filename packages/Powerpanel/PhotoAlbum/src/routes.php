<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/photo-album/', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@index')->name('powerpanel.photo-album.list');
    Route::get('powerpanel/photo-album/', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@index')->name('powerpanel.photo-album.index');

    Route::post('powerpanel/photo-album/get_list/', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@get_list')->name('powerpanel.photo-album.get_list');
    Route::post('powerpanel/photo-album/get_list_New/', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@get_list_New')->name('powerpanel.photo-album.get_list_New');
    Route::post('powerpanel/photo-album/get_list_draft/', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@get_list_draft')->name('powerpanel.photo-album.get_list_draft');
    Route::post('powerpanel/photo-album/get_list_trash/', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@get_list_trash')->name('powerpanel.photo-album.get_list_trash');
    Route::post('powerpanel/photo-album/get_list_favorite/', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@get_list_favorite')->name('powerpanel.photo-album.get_list_favorite');

    Route::get('powerpanel/photo-album/add/', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@edit')->name('powerpanel.photo-album.add');
    Route::post('powerpanel/photo-album/add/', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@handlePost')->name('powerpanel.photo-album.add');

    Route::get('/powerpanel/photo-album/{alias}/edit', ['as' => 'powerpanel.photo-album.edit', 'uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@edit', 'middleware' => 'permission:photo-album-edit']);
    Route::post('/powerpanel/photo-album/{alias}/edit', ['as' => 'powerpanel.photo-album.handleEditPost', 'uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@handlePost', 'middleware' => 'permission:photo-album-edit']);

    Route::post('powerpanel/photo-album/DeleteRecord', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@DeleteRecord');
    Route::post('powerpanel/photo-album/publish', ['uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@publish', 'middleware' => 'permission:photo-album-edit']);
    Route::post('powerpanel/photo-album/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@reorder', 'middleware' => 'permission:photo-album-list']);
    Route::post('powerpanel/photo-album/addpreview', ['as' => 'powerpanel.photo-album.addpreview', 'uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@addPreview', 'middleware' => 'permission:photo-album-create']);
    Route::post('powerpanel/photo-album/getChildData', ['uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@getChildData', 'middleware' => 'permission:photo-album-list']);
    Route::post('powerpanel/photo-album/ApprovedData_Listing', ['uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@ApprovedData_Listing', 'middleware' => 'permission:photo-album-list']);
    Route::post('powerpanel/photo-album/getChildData_rollback', ['uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@getChildData_rollback']);
    Route::post('powerpanel/photo-album/insertComents', ['uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@insertComents']);
    Route::post('powerpanel/photo-album/Get_Comments', ['uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@Get_Comments']);
    Route::post('powerpanel/photo-album/get_builder_list', 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@get_buider_list');
    Route::post('powerpanel/photo-album/rollback-record', ['uses' => 'Powerpanel\PhotoAlbum\Controllers\Powerpanel\PhotoAlbumController@rollBackRecord', 'middleware' => 'permission:photo-album-list']);
});
