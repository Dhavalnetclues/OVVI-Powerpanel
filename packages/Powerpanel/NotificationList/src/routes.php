<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/notificationlist/', 'Powerpanel\NotificationList\Controllers\Powerpanel\NotificationListController@index')->name('powerpanel.notificationlist.list');
    Route::get('powerpanel/notificationlist/', 'Powerpanel\NotificationList\Controllers\Powerpanel\NotificationListController@index')->name('powerpanel.notificationlist.index');
    Route::post('powerpanel/notificationlist/get_list/', 'Powerpanel\NotificationList\Controllers\Powerpanel\NotificationListController@get_list')->name('powerpanel.notificationlist.get_list');
    Route::post('powerpanel/notificationlist/DeleteRecord', 'Powerpanel\NotificationList\Controllers\Powerpanel\NotificationListController@DeleteRecord');
});
