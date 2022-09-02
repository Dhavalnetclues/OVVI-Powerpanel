<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/decision/', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@index')->name('powerpanel.decision.list');
   
    Route::get('powerpanel/decision/', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@index')->name('powerpanel.decision.index');
    
    Route::post('powerpanel/decision/get_list/', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@get_list')->name('powerpanel.decision.get_list');
    Route::post('powerpanel/decision/get_list_New/', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@get_list_New')->name('powerpanel.decision.get_list_New');
    Route::post('powerpanel/decision/get_list_draft/', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@get_list_draft')->name('powerpanel.decision.get_list_draft');
    Route::post('powerpanel/decision/get_list_trash/', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@get_list_trash')->name('powerpanel.decision.get_list_trash');
    Route::post('powerpanel/decision/get_list_favorite/', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@get_list_favorite')->name('powerpanel.decision.get_list_favorite');

    Route::get('powerpanel/decision/add/', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@edit')->name('powerpanel.decision.add');
    Route::post('powerpanel/decision/add/', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@handlePost')->name('powerpanel.decision.add');

    Route::get('/powerpanel/decision/{alias}/edit', ['as' => 'powerpanel.decision.edit', 'uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@edit', 'middleware' => 'permission:decision-edit']);
    Route::post('/powerpanel/decision/{alias}/edit', ['as' => 'powerpanel.decision.handleEditPost', 'uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@handlePost', 'middleware' => 'permission:decision-edit']);

    Route::post('powerpanel/decision/DeleteRecord', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@DeleteRecord');
    Route::post('powerpanel/decision/publish', ['uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@publish', 'middleware' => 'permission:decision-edit']);
    Route::post('powerpanel/decision/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@reorder', 'middleware' => 'permission:decision-list']);
    Route::post('powerpanel/decision/addpreview', ['as' => 'powerpanel.decision.addpreview', 'uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@addPreview', 'middleware' => 'permission:decision-create']);    
    Route::post('powerpanel/decision/getChildData', ['uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@getChildData', 'middleware' => 'permission:decision-list']);
    Route::post('powerpanel/decision/ApprovedData_Listing', ['uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@ApprovedData_Listing', 'middleware' => 'permission:decision-list']);
    Route::post('powerpanel/decision/getChildData_rollback', ['uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@getChildData_rollback']);
     Route::post('powerpanel/decision/insertComents', ['uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@insertComents']);
    Route::post('powerpanel/decision/Get_Comments', ['uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@Get_Comments']);
    Route::post('powerpanel/decision/getCategory', ['uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@getCategory']);
    Route::post('powerpanel/decision/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@getSectorwiseCategoryGrid']);
    Route::post('powerpanel/decision/get_builder_list', 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@get_buider_list');

    Route::post('powerpanel/decision/rollback-record', ['uses' => 'Powerpanel\Decision\Controllers\Powerpanel\DecisionController@rollBackRecord', 'middleware' => 'permission:decision-list']);
});
