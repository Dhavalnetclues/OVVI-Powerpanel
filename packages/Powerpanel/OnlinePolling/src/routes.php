<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::middleware(['permission:online-polling-list'])->get('powerpanel/online-polling', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@index')->name('powerpanel.polls.index');
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/get_list/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@get_list');
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/get_list_New/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@get_list_New');
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/get_list_favorite/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@get_list_favorite');
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/get_list_draft/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@get_list_draft');
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/get_list_trash/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@get_list_trash');
    Route::middleware(['permission:online-polling-publish'])->post('powerpanel/online-polling/publish', ['uses' => 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@publish']);
    Route::middleware(['permission:online-polling-list'])->get('powerpanel/online-polling/reorder/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@reorder')->name('powerpanel.polls.reorder');
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/addpreview/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@addPreview')->name('powerpanel.polls.addpreview');
    Route::middleware(['permission:online-polling-create'])->get('powerpanel/online-polling/add', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@edit')->name('powerpanel.polls.add');
    Route::middleware(['permission:online-polling-create'])->post('powerpanel/online-polling/add/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@handlePost')->name('powerpanel.polls.handleAddPost');
    Route::middleware(['permission:online-polling-edit'])->get('powerpanel/online-polling/{alias}/edit', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@edit')->name('powerpanel.polls.edit');
    Route::middleware(['permission:online-polling-edit'])->post('powerpanel/online-polling/{alias}/edit', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@handlePost')->name('powerpanel/online-polling/handleEditPost');
    Route::middleware(['permission:online-polling-delete'])->post('powerpanel/online-polling/DeleteRecord', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@DeleteRecord');
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/getChildData', ['uses' => 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@getChildData']);
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/ApprovedData_Listing', ['uses' => 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@ApprovedData_Listing']);
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/rollback-record', ['uses' => 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@rollBackRecord']);
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/getChildData_rollback', ['uses' => 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@getChildData_rollback']);
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/insertComents', ['uses' => 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@insertComents']);
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/Get_Comments', ['uses' => 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@Get_Comments']);
    Route::middleware(['permission:online-polling-list'])->post('powerpanel/online-polling/get_builder_list', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsController@get_buider_list');

    Route::middleware(['permission:online-polling-lead-list'])->get('powerpanel/online-polling-lead/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsLeadController@index')->name('powerpanel.online-polling-lead.list');
    Route::middleware(['permission:online-polling-lead-list'])->get('powerpanel/online-polling-lead/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsLeadController@index')->name('powerpanel.online-polling-lead.index');
    Route::middleware(['permission:online-polling-lead-list'])->post('powerpanel/online-polling-lead/get_list/', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsLeadController@get_list')->name('powerpanel.online-polling-lead.get_list');
    Route::middleware(['permission:online-polling-lead-list'])->get('/powerpanel/online-polling-lead/ExportRecord', ['uses' => 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsLeadController@ExportRecord']);
    Route::post('powerpanel/online-polling-lead/DeleteRecord', 'Powerpanel\OnlinePolling\Controllers\powerpanel\PollsLeadController@DeleteRecord');

});
