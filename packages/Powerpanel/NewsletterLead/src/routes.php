<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/newsletter-lead/', 'Powerpanel\NewsletterLead\Controllers\Powerpanel\NewsletterController@index')->name('powerpanel.newsletter-lead.list');
    Route::get('powerpanel/newsletter-lead/', 'Powerpanel\NewsletterLead\Controllers\Powerpanel\NewsletterController@index')->name('powerpanel.newsletter-lead.index');

    Route::post('powerpanel/newsletter-lead/get_list/', 'Powerpanel\NewsletterLead\Controllers\Powerpanel\NewsletterController@get_list')->name('powerpanel.newsletter-lead.get_list');
    Route::get('/powerpanel/newsletter-lead/ExportRecord', ['uses' => 'Powerpanel\NewsletterLead\Controllers\Powerpanel\NewsletterController@ExportRecord', 'middleware' => 'permission:newsletter-lead-list']);
    Route::post('powerpanel/newsletter-lead/DeleteRecord', 'Powerpanel\NewsletterLead\Controllers\Powerpanel\NewsletterController@DeleteRecord');
});
