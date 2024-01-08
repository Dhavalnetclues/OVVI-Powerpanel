<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/cbd-store/', 'Powerpanel\CBDStoreLead\Controllers\Powerpanel\CBDStoreLeadController@index')->name('powerpanel.cbd-store-lead.list');
    Route::get('powerpanel/cbd-store/', 'Powerpanel\CBDStoreLead\Controllers\Powerpanel\CBDStoreLeadController@index')->name('powerpanel.cbd-store-lead.index');

    Route::post('powerpanel/cbd-store/get_list/', 'Powerpanel\CBDStoreLead\Controllers\Powerpanel\CBDStoreLeadController@get_list')->name('powerpanel.cbd-store-lead.get_list');
    Route::get('/powerpanel/cbd-store/ExportRecord', ['uses' => 'Powerpanel\CBDStoreLead\Controllers\Powerpanel\CBDStoreLeadController@ExportRecord', 'middleware' => 'permission:cbd-store-lead-list']);
    Route::post('powerpanel/cbd-store/DeleteRecord', 'Powerpanel\CBDStoreLead\Controllers\Powerpanel\CBDStoreLeadController@DeleteRecord');

    Route::post('/powerpanel/cbd-store/emailreply', ['uses' => 'Powerpanel\CBDStoreLead\Controllers\Powerpanel\CBDStoreLeadController@emailreply']);
    Route::post('/powerpanel/cbd-store/emailforword', ['uses' => 'Powerpanel\CBDStoreLead\Controllers\Powerpanel\CBDStoreLeadController@emailforword']);

});
