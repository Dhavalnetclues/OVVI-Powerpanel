<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/complaint/', 'Powerpanel\ComplaintLead\Controllers\Powerpanel\ComplaintleadController@index')->name('powerpanel.complaint.list');
    Route::get('powerpanel/complaint/', 'Powerpanel\ComplaintLead\Controllers\Powerpanel\ComplaintleadController@index')->name('powerpanel.complaint.index');

    Route::post('powerpanel/complaint/get_list/', 'Powerpanel\ComplaintLead\Controllers\Powerpanel\ComplaintleadController@get_list')->name('powerpanel.complaint.get_list');
    Route::get('/powerpanel/complaint/ExportRecord', ['uses' => 'Powerpanel\ComplaintLead\Controllers\Powerpanel\ComplaintleadController@ExportRecord', 'middleware' => 'permission:complaint-list']);
    Route::post('powerpanel/complaint/DeleteRecord', 'Powerpanel\ComplaintLead\Controllers\Powerpanel\ComplaintleadController@DeleteRecord');

    Route::post('/powerpanel/complaint/emailreply', ['uses' => 'Powerpanel\ComplaintLead\Controllers\Powerpanel\ComplaintleadController@emailreply']);
    Route::post('/powerpanel/complaint/emailforword', ['uses' => 'Powerpanel\ComplaintLead\Controllers\Powerpanel\ComplaintleadController@emailforword']);

});
