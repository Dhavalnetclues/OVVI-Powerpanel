<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/live-user/', 'Powerpanel\LiveUser\Controllers\Powerpanel\LiveUsersController@index')->name('powerpanel.live-user.list');
    Route::get('powerpanel/live-user/', 'Powerpanel\LiveUser\Controllers\Powerpanel\LiveUsersController@index')->name('powerpanel.live-user.index');

    Route::post('powerpanel/live-user/get-list/', 'Powerpanel\LiveUser\Controllers\Powerpanel\LiveUsersController@get_list')->name('powerpanel.live-user.get_list');
    Route::post('powerpanel/live-user/DeleteRecord', 'Powerpanel\LiveUser\Controllers\Powerpanel\LiveUsersController@DeleteRecord');
    Route::post('/powerpanel/live-user/BlockRecord', ['uses' => 'Powerpanel\LiveUser\Controllers\Powerpanel\LiveUsersController@BlockRecord']);
    Route::post('/powerpanel/live-user/block_user', ['uses' => 'Powerpanel\LiveUser\Controllers\Powerpanel\LiveUsersController@block_user']);
    Route::post('/powerpanel/live-user/un_block_user', ['uses' => 'Powerpanel\LiveUser\Controllers\Powerpanel\LiveUsersController@un_block_user']);
    Route::get('/powerpanel/live-user/ExportRecord', ['uses' => 'Powerpanel\LiveUser\Controllers\Powerpanel\LiveUsersController@ExportRecord']);
});
