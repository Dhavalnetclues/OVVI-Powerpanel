<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/static-block', 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@index')->name('powerpanel.static-block.index');
    Route::post('powerpanel/static-block/get_list/', 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@get_list');
    Route::post('powerpanel/static-block/publish', ['uses' => 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@publish', 'middleware' => 'permission:' . Config::get('Constant.MODULE.NAME') . '-edit']);
    Route::get('powerpanel/static-block/reorder/', 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@reorder')->name('powerpanel.static-block.reorder');
    Route::get('powerpanel/static-block/add', 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@edit')->name('powerpanel.static-block.add');
    Route::post('powerpanel/static-block/add/', 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@handlePost')->name('powerpanel.static-block.handleAddPost');
    Route::get('powerpanel/static-block/{alias}/edit', 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@edit')->name('powerpanel.static-block.edit');
    Route::post('powerpanel/static-block/{alias}/edit', 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@handlePost')->name('powerpanel/static-block/handleEditPost');
    Route::post('powerpanel/static-block/DeleteRecord', 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@DeleteRecord');
    Route::post('powerpanel/static-block/get_builder_list', ['uses' => 'Powerpanel\StaticBlocks\Controllers\Powerpanel\StaticBlocksController@get_builder_list']);
});
