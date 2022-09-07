<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/order-lead/', 'Powerpanel\OrderLead\Controllers\Powerpanel\OrderLeadController@index')->name('powerpanel.order-lead-list.list');
    Route::get('powerpanel/order-lead/', 'Powerpanel\OrderLead\Controllers\Powerpanel\OrderLeadController@index')->name('powerpanel.order-lead-list.index');
    Route::post('powerpanel/order-lead/get_list', 'Powerpanel\OrderLead\Controllers\Powerpanel\OrderLeadController@get_list')->name('powerpanel.order-lead-list.get_list');
    Route::get('powerpanel/order-lead/ExportRecord', ['uses' => 'Powerpanel\OrderLead\Controllers\Powerpanel\OrderLeadController@ExportRecord']);
    Route::post('powerpanel/order-lead/DeleteRecord', array('uses' => 'Powerpanel\OrderLead\Controllers\Powerpanel\OrderLeadController@DeleteRecord'));
});
