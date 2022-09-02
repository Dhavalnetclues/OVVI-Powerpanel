<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/alerts/', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@index')->name('powerpanel.alerts.list');
    Route::get('powerpanel/alerts/', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@index')->name('powerpanel.alerts.index');

    Route::post('powerpanel/alerts/get_list/', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@get_list')->name('powerpanel.alerts.get_list');
    Route::post('powerpanel/alerts/get_list_New/', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@get_list_New')->name('powerpanel.alerts.get_list_New');
    Route::post('powerpanel/alerts/get_list_draft/', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@get_list_draft')->name('powerpanel.alerts.get_list_draft');
    Route::post('powerpanel/alerts/get_list_trash/', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@get_list_trash')->name('powerpanel.alerts.get_list_trash');
    Route::post('powerpanel/alerts/get_list_favorite/', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@get_list_favorite')->name('powerpanel.alerts.get_list_favorite');

    Route::get('powerpanel/alerts/add/', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@edit')->name('powerpanel.alerts.add');
    Route::post('powerpanel/alerts/add/', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@handlePost')->name('powerpanel.alerts.add');

    Route::get('/powerpanel/alerts/{alias}/edit', ['as' => 'powerpanel.alerts.edit', 'uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@edit', 'middleware' => 'permission:alerts-edit']);
    Route::post('/powerpanel/alerts/{alias}/edit', ['as' => 'powerpanel.alerts.handleEditPost', 'uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@handlePost', 'middleware' => 'permission:alerts-edit']);

    Route::post('powerpanel/alerts/DeleteRecord', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@DeleteRecord');
    Route::post('powerpanel/alerts/publish', ['uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@publish', 'middleware' => 'permission:alerts-edit']);
    Route::post('powerpanel/alerts/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@reorder', 'middleware' => 'permission:alerts-list']);

    Route::post('powerpanel/alerts/getChildData', ['uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@getChildData', 'middleware' => 'permission:alerts-list']);
    Route::post('powerpanel/alerts/ApprovedData_Listing', ['uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@ApprovedData_Listing', 'middleware' => 'permission:alerts-list']);
    Route::post('powerpanel/alerts/getChildData_rollback', ['uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@getChildData_rollback']);
    Route::post('powerpanel/alerts/insertComents', ['uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@insertComents']);
    Route::post('powerpanel/alerts/Get_Comments', ['uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@Get_Comments']);
    Route::post('powerpanel/alerts/selectRecords', ['uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@selectRecords']);

    Route::post('powerpanel/alerts/rollback-record', ['uses' => 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@rollBackRecord', 'middleware' => 'permission:alerts-list']);

    Route::post('powerpanel/alerts/get_builder_list', 'Powerpanel\Alerts\Controllers\Powerpanel\AlertsController@get_buider_list');
});
