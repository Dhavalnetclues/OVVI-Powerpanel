<?php

Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/workflow/', 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@index')->name('powerpanel.workflow.list');
    Route::get('powerpanel/workflow/', 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@index')->name('powerpanel.workflow.index');


    Route::get('/powerpanel/workflow/add', ['as' => 'powerpanel.workflow.add', 'uses' =>  'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@edit', 'middleware' => 'permission:workflow-create']);
    Route::post('/powerpanel/workflow/add', ['as' => 'powerpanel.workflow.handleAddPost', 'uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@handlePost', 'middleware' => 'permission:workflow-create']);

    Route::get('/powerpanel/workflow/{alias}/edit', ['as' => 'powerpanel.workflow.edit', 'uses' =>   'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@edit', 'middleware' => 'permission:workflow-edit']);
    Route::post('/powerpanel/workflow/{alias}/edit', ['as' => 'powerpanel.workflow.handleEditPost', 'uses' =>   'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@handlePost', 'middleware' => 'permission:workflow-edit']);
    
    Route::post('powerpanel/workflow/get-admin', ['uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@getAdmins']);
    Route::post('powerpanel/workflow/get-admin-users', ['uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@getAdminUsers']);

    Route::post('powerpanel/workflow/get-category', ['uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@getCategory']);
    Route::post('powerpanel/workflow/get-modulebycategory', ['uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@getCategoryWiseModules']);
    Route::post('powerpanel/workflow/get-module-by-role', ['uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@getModulesByRole']);
    Route::post('powerpanel/workflow/check-wfexists', ['uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@wfExists']);
    Route::post('powerpanel/workflow/getChildData', ['uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@getChildData']);
    Route::post('powerpanel/workflow/get_list/', 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@get_list')->name('powerpanel.workflow.get_list');
    
    Route::post('powerpanel/workflow/DeleteRecord', 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@DeleteRecord');
    Route::post('powerpanel/workflow/publish', ['uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@publish', 'middleware' => 'permission:workflow-edit']);
    Route::post('powerpanel/workflow/reorder', ['as' => 'workflow.reorder', 'uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@reorder', 'middleware' => 'permission:workflow-list']);
    Route::post('powerpanel/workflow/insertComents', ['as' => 'pages.index', 'uses' => 'Powerpanel\Workflow\Controllers\Powerpanel\WorkflowController@insertComents']);
});
