<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/submit-tickets/', 'Powerpanel\TicketList\Controllers\Powerpanel\SubmitTicketsController@index')->name('powerpanel.submit-tickets.list');
    Route::get('powerpanel/submit-tickets/', 'Powerpanel\TicketList\Controllers\Powerpanel\SubmitTicketsController@index')->name('powerpanel.submit-tickets.index');
    
    Route::post('powerpanel/submit-tickets/get_list/', 'Powerpanel\TicketList\Controllers\Powerpanel\SubmitTicketsController@get_list')->name('powerpanel.submit-tickets.get_list');
   Route::get('/powerpanel/submit-tickets/ExportRecord', ['uses' => 'Powerpanel\TicketList\Controllers\Powerpanel\SubmitTicketsController@ExportRecord', 'middleware' => 'permission:submit-tickets-list']);
   Route::post('powerpanel/submit-tickets/DeleteRecord', 'Powerpanel\TicketList\Controllers\Powerpanel\SubmitTicketsController@DeleteRecord');
    Route::post('/powerpanel/submit-tickets/changeticketstatus', ['uses' => 'Powerpanel\TicketList\Controllers\Powerpanel\SubmitTicketsController@changeticketstatus']);    
   Route::post('/powerpanel/submit-tickets/emailreply', ['uses' => 'Powerpanel\TicketList\Controllers\Powerpanel\SubmitTicketsController@emailreply', 'middleware' => 'permission:submit-tickets-list']);
});
