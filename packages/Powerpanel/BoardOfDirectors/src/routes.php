<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/boardofdirectors/', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@index')->name('powerpanel.boardofdirectors.list');
    Route::get('powerpanel/boardofdirectors/', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@index')->name('powerpanel.boardofdirectors.index');
    
    Route::post('powerpanel/boardofdirectors/get_list/', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@get_list')->name('powerpanel.boardofdirectors.get_list');
    Route::post('powerpanel/boardofdirectors/get_list_New/', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@get_list_New')->name('powerpanel.boardofdirectors.get_list_New');
    Route::post('powerpanel/boardofdirectors/get_list_draft/', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@get_list_draft')->name('powerpanel.boardofdirectors.get_list_draft');
    Route::post('powerpanel/boardofdirectors/get_list_trash/', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@get_list_trash')->name('powerpanel.boardofdirectors.get_list_trash');
    Route::post('powerpanel/boardofdirectors/get_list_favorite/', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@get_list_favorite')->name('powerpanel.boardofdirectors.get_list_favorite');

    Route::get('powerpanel/boardofdirectors/add/', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@edit')->name('powerpanel.boardofdirectors.add');
    Route::post('powerpanel/boardofdirectors/add/', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@handlePost')->name('powerpanel.boardofdirectors.add');

    Route::get('/powerpanel/boardofdirectors/{alias}/edit', ['as' => 'powerpanel.boardofdirectors.edit', 'uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@edit', 'middleware' => 'permission:boardofdirectors-edit']);
    Route::post('/powerpanel/boardofdirectors/{alias}/edit', ['as' => 'powerpanel.boardofdirectors.handleEditPost', 'uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@handlePost', 'middleware' => 'permission:boardofdirectors-edit']);

    Route::post('powerpanel/boardofdirectors/DeleteRecord', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@DeleteRecord');
    Route::post('powerpanel/boardofdirectors/publish', ['uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@publish', 'middleware' => 'permission:boardofdirectors-edit']);
    Route::post('powerpanel/boardofdirectors/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@reorder', 'middleware' => 'permission:boardofdirectors-list']);
    Route::post('powerpanel/boardofdirectors/addpreview', ['as' => 'powerpanel.boardofdirectors.addpreview', 'uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@addPreview', 'middleware' => 'permission:boardofdirectors-create']);             
    Route::post('powerpanel/boardofdirectors/getChildData', ['uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@getChildData', 'middleware' => 'permission:boardofdirectors-list']);
    Route::post('powerpanel/boardofdirectors/ApprovedData_Listing', ['uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@ApprovedData_Listing', 'middleware' => 'permission:boardofdirectors-list']);
    Route::post('powerpanel/boardofdirectors/getChildData_rollback', ['uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@getChildData_rollback']);
    Route::post('powerpanel/boardofdirectors/insertComents', ['uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@insertComents']);
    Route::post('powerpanel/boardofdirectors/Get_Comments', ['uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@Get_Comments']);
    Route::post('powerpanel/boardofdirectors/rollback-record', ['uses' => 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@rollBackRecord', 'middleware' => 'permission:boardofdirectors-list']);
    Route::post('powerpanel/boardofdirectors/get_builder_list', 'Powerpanel\BoardOfDirectors\Controllers\Powerpanel\BoardOfDirectorsController@get_buider_list');
    
});
