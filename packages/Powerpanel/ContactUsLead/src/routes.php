<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/contact-us/', 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@index')->name('powerpanel.contact-us.list');
    Route::get('powerpanel/contact-us/', 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@index')->name('powerpanel.contact-us.index');

    Route::post('powerpanel/contact-us/get_list/', 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@get_list')->name('powerpanel.contact-us.get_list');
    Route::get('/powerpanel/contact-us/ExportRecord', ['uses' => 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@ExportRecord', 'middleware' => 'permission:contact-us-list']);
    Route::post('powerpanel/contact-us/DeleteRecord', 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@DeleteRecord');

    Route::post('/powerpanel/contact-us/emailreply', ['uses' => 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@emailreply']);
    Route::post('/powerpanel/contact-us/emailforword', ['uses' => 'Powerpanel\ContactUsLead\Controllers\Powerpanel\ContactleadController@emailforword']);

});
