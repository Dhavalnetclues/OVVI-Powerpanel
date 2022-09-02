<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/banners/', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@index')->name('powerpanel.banners.list');
    Route::get('powerpanel/banners/', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@index')->name('powerpanel.banners.index');
    
    Route::post('powerpanel/banners/get_list/', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@get_list')->name('powerpanel.banners.get_list');
    Route::post('powerpanel/banners/get_list_New/', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@get_list_New')->name('powerpanel.banners.get_list_New');
    Route::post('powerpanel/banners/get_list_draft/', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@get_list_draft')->name('powerpanel.banners.get_list_draft');
    Route::post('powerpanel/banners/get_list_trash/', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@get_list_trash')->name('powerpanel.banners.get_list_trash');
    Route::post('powerpanel/banners/get_list_favorite/', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@get_list_favorite')->name('powerpanel.banners.get_list_favorite');

    Route::get('powerpanel/banners/add/', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@edit')->name('powerpanel.banners.add');
    Route::post('powerpanel/banners/add/', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@handlePost')->name('powerpanel.banners.add');
    
    Route::get('powerpanel/banners/{alias}/edit', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@edit', 'middleware' => 'permission:banners-edit'])->name('powerpanel.banners.edit');
    Route::post('powerpanel/banners/{alias}/edit', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@handlePost', 'middleware' => 'permission:banners-edit'])->name('powerpanel.banners.handleEditPost');

    Route::post('powerpanel/banners/DeleteRecord', 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@DeleteRecord');
    Route::post('powerpanel/banners/publish', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@publish', 'middleware' => 'permission:banners-edit']);
    Route::post('powerpanel/banners/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@reorder', 'middleware' => 'permission:banners-list']);
    Route::post('powerpanel/banners/addpreview', ['as' => 'powerpanel.banners.addpreview', 'uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@addPreview', 'middleware' => 'permission:banners-create']);             
    Route::post('powerpanel/banners/getChildData', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@getChildData', 'middleware' => 'permission:banners-list']);
    Route::post('powerpanel/banners/ApprovedData_Listing', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@ApprovedData_Listing', 'middleware' => 'permission:banners-list']);
    Route::post('powerpanel/banners/getChildData_rollback', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@getChildData_rollback']);
    Route::post('powerpanel/banners/insertComents', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@insertComents']);
    Route::post('powerpanel/banners/Get_Comments', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@Get_Comments']);
    Route::post('powerpanel/banners/rollback-record', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@rollBackRecord', 'middleware' => 'permission:banners-list']);

    Route::post('/powerpanel/banners/selectRecords', ['uses' => 'Powerpanel\Banner\Controllers\Powerpanel\BannerController@selectRecords']);

});
