<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/career-lead/', 'Powerpanel\CareerLead\Controllers\Powerpanel\CareerleadController@index')->name('powerpanel.career.list');
    Route::get('powerpanel/career-lead/', 'Powerpanel\CareerLead\Controllers\Powerpanel\CareerleadController@index')->name('powerpanel.career.index');

    Route::post('powerpanel/career-lead/get_list/', 'Powerpanel\CareerLead\Controllers\Powerpanel\CareerleadController@get_list')->name('powerpanel.career.get_list');
    Route::get('/powerpanel/career-lead/ExportRecord', ['uses' => 'Powerpanel\CareerLead\Controllers\Powerpanel\CareerleadController@ExportRecord', 'middleware' => 'permission:career-list']);
    Route::post('powerpanel/career-lead/DeleteRecord', 'Powerpanel\CareerLead\Controllers\Powerpanel\CareerleadController@DeleteRecord');

    Route::post('/powerpanel/career-lead/emailreply', ['uses' => 'Powerpanel\CareerLead\Controllers\Powerpanel\CareerleadController@emailreply']);
    Route::post('/powerpanel/career-lead/emailforword', ['uses' => 'Powerpanel\CareerLead\Controllers\Powerpanel\CareerleadController@emailforword']);

});
