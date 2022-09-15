<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/contact-lead/', 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@index')->name('powerpanel.contact-lead.list');
    Route::get('powerpanel/contact-lead/', 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@index')->name('powerpanel.contact-lead.index');

    Route::post('powerpanel/contact-lead/get_list/', 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@get_list')->name('powerpanel.contact-lead.get_list');
    Route::get('/powerpanel/contact-lead/ExportRecord', ['uses' => 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@ExportRecord', 'middleware' => 'permission:contact-us-list']);
    Route::post('powerpanel/contact-lead/DeleteRecord', 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@DeleteRecord');

    Route::post('/powerpanel/contact-lead/emailreply', ['uses' => 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@emailreply']);
    Route::post('/powerpanel/contact-lead/emailforword', ['uses' => 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@emailforword']);

});
