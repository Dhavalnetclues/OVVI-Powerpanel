<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/media-manager/', 'Powerpanel\MediaManager\Controllers\Powerpanel\MediaManagerController@index')->name('powerpanel.media-manager.list');
    Route::get('powerpanel/media-manager/', 'Powerpanel\MediaManager\Controllers\Powerpanel\MediaManagerController@index')->name('powerpanel.media-manager.index');
    
});
