<?php

Route::group(['middleware' => ['web', 'auth']], function() {
     Route::get('powerpanel/messagingsystem/', 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@index')->name('powerpanel.messagingsystem.list');
    Route::get('powerpanel/messagingsystem/', 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@index')->name('powerpanel.messagingsystem.index');
    
    Route::post('powerpanel/messagingsystem/recentid', array('as' => 'powerpanel/messagingsystem/recentid', 'uses' => 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@GetRecentid'));
    Route::post('powerpanel/messagingsystem/getnewmessage', array('as' => 'powerpanel/messagingsystem/getnewmessage', 'uses' => 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@GetNewMessage'));
    Route::post('powerpanel/messagingsystem/getnewmessagecounter', array('as' => 'powerpanel/messagingsystem/getnewmessagecounter', 'uses' => 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@GetNewMessageCounter'));
    Route::post('powerpanel/messagingsystem/removesinglemsg', array('as' => 'powerpanel/messagingsystem/removesinglemsg', 'uses' => 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@RemoveSingMsg'));
    Route::post('powerpanel/messagingsystem/insermessagedata', array('as' => 'powerpanel/messagingsystem/insermessagedata', 'uses' => 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@InserMessageData'));
    Route::post('powerpanel/messagingsystem/forwordtomessage', array('as' => 'powerpanel/messagingsystem/forwordtomessage', 'uses' => 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@ForwordMsg'));
    Route::post('powerpanel/messagingsystem/messageiddata', array('as' => 'powerpanel/messagingsystem/messageiddata', 'uses' => 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@GetMessageidData'));
    Route::post('powerpanel/messagingsystem/clearchat', array('as' => 'powerpanel/messagingsystem/clearchat', 'uses' => 'Powerpanel\MessagingSystem\Controllers\Powerpanel\MessagingSystemController@ClearChat'));
   

});
