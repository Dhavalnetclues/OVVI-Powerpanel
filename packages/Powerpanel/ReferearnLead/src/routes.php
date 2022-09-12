<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/referearn-lead/', 'Powerpanel\ReferearnLead\Controllers\Powerpanel\ReferearnController@index')->name('powerpanel.referearn-lead.list');
    Route::get('powerpanel/referearn-lead/', 'Powerpanel\ReferearnLead\Controllers\Powerpanel\ReferearnController@index')->name('powerpanel.referearn-lead.index');

    Route::post('powerpanel/referearn-lead/get_list/', 'Powerpanel\ReferearnLead\Controllers\Powerpanel\ReferearnController@get_list')->name('powerpanel.referearn-lead.get_list');
    Route::get('/powerpanel/referearn-lead/ExportRecord', ['uses' => 'Powerpanel\ReferearnLead\Controllers\Powerpanel\ReferearnController@ExportRecord', 'middleware' => 'permission:referearn-lead-list']);
    Route::post('powerpanel/referearn-lead/DeleteRecord', 'Powerpanel\ReferearnLead\Controllers\Powerpanel\ReferearnController@DeleteRecord');
});
