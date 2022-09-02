<?php

Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/events/', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@index')->name('powerpanel.events.list');
    Route::get('powerpanel/events/', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@index')->name('powerpanel.events.index');

    Route::post('powerpanel/events/get_list/', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@get_list')->name('powerpanel.events.get_list');
    Route::post('powerpanel/events/get_list_New/', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@get_list_New')->name('powerpanel.events.get_list_New');
    Route::post('powerpanel/events/get_list_draft/', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@get_list_draft')->name('powerpanel.events.get_list_draft');
    Route::post('powerpanel/events/get_list_trash/', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@get_list_trash')->name('powerpanel.events.get_list_trash');
    Route::post('powerpanel/events/get_list_favorite/', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@get_list_favorite')->name('powerpanel.events.get_list_favorite');

    Route::get('powerpanel/events/add/', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@edit')->name('powerpanel.events.add');
    Route::post('powerpanel/events/add/', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@handlePost')->name('powerpanel.events.add');

    Route::get('/powerpanel/events/{alias}/edit', ['as' => 'powerpanel.events.edit', 'uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@edit', 'middleware' => 'permission:events-edit']);
    Route::post('/powerpanel/events/{alias}/edit', ['as' => 'powerpanel.events.handleEditPost', 'uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@handlePost', 'middleware' => 'permission:events-edit']);

    Route::post('powerpanel/events/DeleteRecord', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@DeleteRecord');
    Route::post('powerpanel/events/publish', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@publish', 'middleware' => 'permission:events-edit']);
    Route::post('powerpanel/events/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@reorder', 'middleware' => 'permission:events-list']);
    Route::post('powerpanel/events/addpreview', ['as' => 'powerpanel.events.addpreview', 'uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@addPreview', 'middleware' => 'permission:events-create']);
    Route::post('powerpanel/events/getChildData', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@getChildData', 'middleware' => 'permission:events-list']);
    Route::post('powerpanel/events/ApprovedData_Listing', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@ApprovedData_Listing', 'middleware' => 'permission:events-list']);
    Route::post('powerpanel/events/getChildData_rollback', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@getChildData_rollback']);
    Route::post('powerpanel/events/insertComents', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@insertComents']);
    Route::post('powerpanel/events/Get_Comments', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@Get_Comments']);
    Route::post('powerpanel/events/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@getSectorwiseCategoryGrid']);

    Route::post('powerpanel/events/get_builder_list', 'Powerpanel\Events\Controllers\Powerpanel\EventsController@get_buider_list');
    Route::post('powerpanel/events/rollback-record', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@rollBackRecord', 'middleware' => 'permission:events-list']);

    Route::get('powerpanel/events-lead/', 'Powerpanel\Events\Controllers\Powerpanel\EventsLeadController@index')->name('powerpanel.events-lead.list');
    Route::get('powerpanel/events-lead/', 'Powerpanel\Events\Controllers\Powerpanel\EventsLeadController@index')->name('powerpanel.events-lead.index');
    Route::post('powerpanel/events/getCategory', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsController@getCategory']);
    Route::post('powerpanel/events-lead/get_list/', 'Powerpanel\Events\Controllers\Powerpanel\EventsLeadController@get_list')->name('powerpanel.events-lead.get_list');
    Route::get('/powerpanel/events-lead/ExportRecord', ['uses' => 'Powerpanel\Events\Controllers\Powerpanel\EventsLeadController@ExportRecord', 'middleware' => 'permission:events-lead-list']);
    Route::post('powerpanel/events-lead/DeleteRecord', 'Powerpanel\Events\Controllers\Powerpanel\EventsLeadController@DeleteRecord');
});
