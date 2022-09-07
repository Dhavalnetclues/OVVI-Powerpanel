<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/reseller-leads/', 'Powerpanel\Reseller\Controllers\Powerpanel\ResellerleadController@index')->name('powerpanel.reseller-leads.list');
    Route::get('powerpanel/reseller-leads/', 'Powerpanel\Reseller\Controllers\Powerpanel\ResellerleadController@index')->name('powerpanel.reseller-leads.index');
    
    Route::post('powerpanel/reseller-leads/get_list/', 'Powerpanel\Reseller\Controllers\Powerpanel\ResellerleadController@get_list')->name('powerpanel.reseller-leads.get_list');
    Route::get('/powerpanel/reseller-leads/ExportRecord', ['uses' => 'Powerpanel\ResellerLead\Controllers\Powerpanel\ResellerleadController@ExportRecord', 'middleware' => 'permission:reseller-leads-list']);
    Route::post('powerpanel/reseller-leads/DeleteRecord', 'Powerpanel\ResellerLead\Controllers\Powerpanel\ResellerleadController@DeleteRecord');
    
});
