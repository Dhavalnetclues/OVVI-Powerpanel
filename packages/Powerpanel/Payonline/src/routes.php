<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/payonline/', 'Powerpanel\Payonline\Controllers\Powerpanel\PayonlineController@index')->name('powerpanel.payonline.list');
    Route::get('powerpanel/payonline/', 'Powerpanel\Payonline\Controllers\Powerpanel\PayonlineController@index')->name('powerpanel.payonline.index');

    Route::post('powerpanel/payonline/get_list/', 'Powerpanel\Payonline\Controllers\Powerpanel\PayonlineController@get_list')->name('powerpanel.payonline.get_list');
    Route::get('/powerpanel/payonline/ExportRecord', ['uses' => 'Powerpanel\Payonline\Controllers\Powerpanel\PayonlineController@ExportRecord']);
    Route::post('powerpanel/payonline/DeleteRecord', 'Powerpanel\Payonline\Controllers\Powerpanel\PayonlineController@DeleteRecord');

});
