<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/popup', 'Powerpanel\PopupContent\Controllers\Powerpanel\PopupController@index')->name('powerpanel.popup.index');
     Route::post('powerpanel/popup/get_list/', 'Powerpanel\PopupContent\Controllers\Powerpanel\PopupController@get_list');
    Route::post('powerpanel/popup/publish', ['uses' => 'Powerpanel\PopupContent\Controllers\Powerpanel\PopupController@publish', 'middleware' => 'permission:' . Config::get('Constant.MODULE.NAME') . '-edit']);
    Route::get('powerpanel/popup/add', 'Powerpanel\PopupContent\Controllers\Powerpanel\PopupController@edit')->name('powerpanel.popup.add');
    Route::post('powerpanel/popup/add/', 'Powerpanel\PopupContent\Controllers\Powerpanel\PopupController@handlePost')->name('powerpanel.popup.handleAddPost');
    Route::get('powerpanel/popup/{alias}/edit', 'Powerpanel\PopupContent\Controllers\Powerpanel\PopupController@edit')->name('powerpanel.popup.edit');
    Route::post('powerpanel/popup/{alias}/edit', 'Powerpanel\PopupContent\Controllers\Powerpanel\PopupController@handlePost')->name('powerpanel/popup/handleEditPost');
    Route::post('powerpanel/popup/DeleteRecord', 'Powerpanel\PopupContent\Controllers\Powerpanel\PopupController@DeleteRecord');
    Route::post('/powerpanel/popup/selectRecords', ['uses' => 'Powerpanel\PopupContent\Controllers\Powerpanel\PopupController@selectRecords']);
});
