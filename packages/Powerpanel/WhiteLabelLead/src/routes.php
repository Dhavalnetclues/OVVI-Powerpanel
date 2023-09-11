<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/white-label/', 'Powerpanel\WhiteLabelLead\Controllers\Powerpanel\WhiteLabelLeadController@index')->name('powerpanel.contact-lead.list');
    Route::get('powerpanel/white-label/', 'Powerpanel\WhiteLabelLead\Controllers\Powerpanel\WhiteLabelLeadController@index')->name('powerpanel.contact-lead.index');

    Route::post('powerpanel/white-label/get_list/', 'Powerpanel\WhiteLabelLead\Controllers\Powerpanel\WhiteLabelLeadController@get_list')->name('powerpanel.contact-lead.get_list');
    Route::get('/powerpanel/white-label/ExportRecord', ['uses' => 'Powerpanel\WhiteLabelLead\Controllers\Powerpanel\WhiteLabelLeadController@ExportRecord', 'middleware' => 'permission:contact-us-list']);
    Route::post('powerpanel/white-label/DeleteRecord', 'Powerpanel\WhiteLabelLead\Controllers\Powerpanel\WhiteLabelLeadController@DeleteRecord');

    Route::post('/powerpanel/white-label/emailreply', ['uses' => 'Powerpanel\WhiteLabelLead\Controllers\Powerpanel\WhiteLabelLeadController@emailreply']);
    Route::post('/powerpanel/white-label/emailforword', ['uses' => 'Powerpanel\WhiteLabelLead\Controllers\Powerpanel\WhiteLabelLeadController@emailforword']);

});
