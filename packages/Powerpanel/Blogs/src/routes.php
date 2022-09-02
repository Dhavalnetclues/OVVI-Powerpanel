<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/blogs', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@index')->name('powerpanel.blogs.index');
    Route::post('powerpanel/blogs/get_list/', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@get_list');
    Route::post('powerpanel/blogs/get_list_New/', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@get_list_New');
    Route::post('powerpanel/blogs/get_list_favorite/', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@get_list_favorite');
    Route::post('powerpanel/blogs/get_list_draft/', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@get_list_draft');
    Route::post('powerpanel/blogs/get_list_trash/', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@get_list_trash');

    Route::post('powerpanel/blogs/publish', ['uses' => 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@publish', 'middleware' => 'permission:' . Config::get('Constant.MODULE.NAME') . '-edit']);

    Route::get('powerpanel/blogs/reorder/', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@reorder')->name('powerpanel.blogs.reorder');

    Route::post('powerpanel/blogs/addpreview/', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@addPreview')->name('powerpanel.blogs.addpreview');

    Route::get('powerpanel/blogs/add', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@edit')->name('powerpanel.blogs.add');
    Route::post('powerpanel/blogs/add/', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@handlePost')->name('powerpanel.blogs.handleAddPost');
    
    Route::get('powerpanel/blogs/{alias}/edit', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@edit')->name('powerpanel.blogs.edit');
    Route::post('powerpanel/blogs/{alias}/edit', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@handlePost')->name('powerpanel/blogs/handleEditPost');

    Route::post('powerpanel/blogs/DeleteRecord', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@DeleteRecord');

    Route::post('powerpanel/blogs/getChildData', ['uses' => 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@getChildData', 'middleware' => 'permission:blogs-list']);
    Route::post('powerpanel/blogs/ApprovedData_Listing', ['uses' => 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@ApprovedData_Listing', 'middleware' => 'permission:blogs-list']);
    Route::post('powerpanel/blogs/rollback-record', ['uses' => 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@rollBackRecord', 'middleware' => 'permission:blogs-list']);

    Route::post('powerpanel/blogs/getChildData_rollback', ['uses' => 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@getChildData_rollback']);
    
    Route::post('powerpanel/blogs/insertComents', ['uses' => 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@insertComents']);
    Route::post('powerpanel/blogs/Get_Comments', ['uses' => 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@Get_Comments']);
    
    Route::post('powerpanel/blogs/get_builder_list', 'Powerpanel\Blogs\Controllers\powerpanel\BlogsController@get_buider_list');
});
