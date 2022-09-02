<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/team/', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@index')->name('powerpanel.team.list');
    Route::get('powerpanel/team/', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@index')->name('powerpanel.team.index');
    
    Route::post('powerpanel/team/get_list/', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@get_list')->name('powerpanel.team.get_list');
    Route::post('powerpanel/team/get_list_New/', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@get_list_New')->name('powerpanel.team.get_list_New');
    Route::post('powerpanel/team/get_list_draft/', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@get_list_draft')->name('powerpanel.team.get_list_draft');
    Route::post('powerpanel/team/get_list_trash/', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@get_list_trash')->name('powerpanel.team.get_list_trash');
    Route::post('powerpanel/team/get_list_favorite/', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@get_list_favorite')->name('powerpanel.team.get_list_favorite');

    Route::get('powerpanel/team/add/', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@edit')->name('powerpanel.team.add');
    Route::post('powerpanel/team/add/', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@handlePost')->name('powerpanel.team.add');

    Route::get('/powerpanel/team/{alias}/edit', ['as' => 'powerpanel.team.edit', 'uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@edit', 'middleware' => 'permission:team-edit']);
    Route::post('/powerpanel/team/{alias}/edit', ['as' => 'powerpanel.team.handleEditPost', 'uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@handlePost', 'middleware' => 'permission:team-edit']);

    Route::post('powerpanel/team/DeleteRecord', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@DeleteRecord');
    Route::post('powerpanel/team/publish', ['uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@publish', 'middleware' => 'permission:team-edit']);
    Route::post('powerpanel/team/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@reorder', 'middleware' => 'permission:team-list']);
    Route::post('powerpanel/team/addpreview', ['as' => 'powerpanel.team.addpreview', 'uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@addPreview', 'middleware' => 'permission:team-create']);             
    Route::post('powerpanel/team/getChildData', ['uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@getChildData', 'middleware' => 'permission:team-list']);
    Route::post('powerpanel/team/ApprovedData_Listing', ['uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@ApprovedData_Listing', 'middleware' => 'permission:team-list']);
    Route::post('powerpanel/team/getChildData_rollback', ['uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@getChildData_rollback']);
    Route::post('powerpanel/team/insertComents', ['uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@insertComents']);
    Route::post('powerpanel/team/Get_Comments', ['uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@Get_Comments']);
    Route::post('powerpanel/team/rollback-record', ['uses' => 'Powerpanel\Team\Controllers\Powerpanel\TeamController@rollBackRecord', 'middleware' => 'permission:team-list']);
     Route::post('powerpanel/team/get_builder_list', 'Powerpanel\Team\Controllers\Powerpanel\TeamController@get_buider_list');
    
});
