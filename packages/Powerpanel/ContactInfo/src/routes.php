<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/contact-info/', 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@index')->name('powerpanel.contact-info.list');
    Route::get('powerpanel/contact-info/', 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@index')->name('powerpanel.contact-info.index');
    
    Route::post('powerpanel/contact-info/get_list/', 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@get_list');
    Route::post('powerpanel/contact-info/DeleteRecord/', 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@DeleteRecord')->name('powerpanel.contact-info.delete');

    Route::get('powerpanel/contact-info/', 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@index')->name('powerpanel.contact-info.list');
Route::post('powerpanel/contact-info/publish', ['uses' => 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@publish', 'middleware' => 'permission:contact-info-edit']);
    Route::get('powerpanel/contact-info/add', 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@edit')->name('powerpanel.contact-info.add');
    Route::post('powerpanel/contact-info/add/', 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@handlePost')->name('powerpanel.contact-info.handleAddPost');

    Route::get('powerpanel/contact-info/{alias}/edit', 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@edit')->name('powerpanel.contact-info.edit');
    Route::post('powerpanel/contact-info/{alias}/edit', 'Powerpanel\ContactInfo\Controllers\Powerpanel\ContactInfoController@handlePost')->name('powerpanel/contact-info/handleEditPost');

});
