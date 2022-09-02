<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    
    Route::get('powerpanel/blocked-ips/', 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@index')->name('powerpanel.blocked-ips.list');
    Route::get('powerpanel/blocked-ips/', 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@index')->name('powerpanel.blocked-ips.index');
    
    Route::post('powerpanel/blocked-ips/get-list/', 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@get_list')->name('powerpanel.blocked-ips.get_list');
    Route::get('/powerpanel/blocked-ips/ExportRecord', ['uses' => 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@ExportRecord', 'middleware' => 'permission:blocked-ips-list']);
    Route::post('powerpanel/blocked-ips/DeleteRecord', 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@DeleteRecord');

    Route::get('/powerpanel/blocked-ips/add', array('uses' => 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@edit'));
    Route::post('/powerpanel/blocked-ips/add', ['as' => 'powerpanel.blocked-ips.handleAddPost', 'uses' => 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@handlePost']);

    Route::get('/powerpanel/blocked-ips/{alias}/edit', ['as' => 'powerpanel.blocked-ips.edit', 'uses' => 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@edit', 'middleware' => 'permission:blocked-ips-edit']);
    Route::post('/powerpanel/blocked-ips/{alias}/edit', ['as' => 'powerpanel.blocked-ips.handleEditPost', 'uses' => 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@handlePost', 'middleware' => 'permission:blocked-ips-edit']);

    Route::post('/powerpanel/blocked-ips/updateblockid', array('as' => 'powerpanel/blocked-ips/updateblockid', 'uses' => 'Powerpanel\BlockedIP\Controllers\Powerpanel\BlockedIpsController@UpdateData'));
       
});
