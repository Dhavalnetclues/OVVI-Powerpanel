<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/getdemo-leads/', 'Powerpanel\GetdemoLead\Controllers\Powerpanel\GetdemoleadController@index')->name('powerpanel.getdemo-leads.list');
    Route::get('powerpanel/getdemo-leads/', 'Powerpanel\GetdemoLead\Controllers\Powerpanel\GetdemoleadController@index')->name('powerpanel.getdemo-leads.index');
    
    Route::post('powerpanel/getdemo-leads/get_list/', 'Powerpanel\GetdemoLead\Controllers\Powerpanel\GetdemoleadController@get_list')->name('powerpanel.getdemo-leads.get_list');
   Route::get('/powerpanel/getdemo-leads/ExportRecord', ['uses' => 'Powerpanel\GetdemoLead\Controllers\Powerpanel\GetdemoleadController@ExportRecord', 'middleware' => 'permission:getdemo-leads-list']);
   Route::post('powerpanel/getdemo-leads/DeleteRecord', 'Powerpanel\GetdemoLead\Controllers\Powerpanel\GetdemoleadController@DeleteRecord');
       
});
