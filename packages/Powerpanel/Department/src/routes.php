<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/department/', 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@index')->name('powerpanel.department.list');
    Route::get('powerpanel/department/', 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@index')->name('powerpanel.department.index');
    
    Route::post('powerpanel/department/get_list/', 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@get_list')->name('powerpanel.department.get_list');
    Route::post('powerpanel/department/get_list_New/', 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@get_list_New')->name('powerpanel.department.get_list_New');
   
    Route::get('powerpanel/department/add/', 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@edit')->name('powerpanel.department.add');
    Route::post('powerpanel/department/add/', 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@handlePost')->name('powerpanel.department.add');

    Route::get('/powerpanel/department/{alias}/edit', ['as' => 'powerpanel.department.edit', 'uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@edit', 'middleware' => 'permission:department-edit']);
    Route::post('/powerpanel/department/{alias}/edit', ['as' => 'powerpanel.department.handleEditPost', 'uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@handlePost', 'middleware' => 'permission:department-edit']);

    Route::post('powerpanel/department/DeleteRecord', 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@DeleteRecord');
    Route::post('powerpanel/department/publish', ['uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@publish', 'middleware' => 'permission:department-edit']);
    Route::post('powerpanel/department/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@reorder', 'middleware' => 'permission:department-list']);
       
    Route::post('powerpanel/department/getChildData', ['uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@getChildData', 'middleware' => 'permission:department-list']);
    Route::post('powerpanel/department/ApprovedData_Listing', ['uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@ApprovedData_Listing', 'middleware' => 'permission:department-list']);
    Route::post('powerpanel/department/getChildData_rollback', ['uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@getChildData_rollback']);
    Route::post('powerpanel/department/insertComents', ['uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@insertComents']);
    Route::post('powerpanel/department/Get_Comments', ['uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@Get_Comments']);

    Route::post('powerpanel/department/rollback-record', ['uses' => 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@rollBackRecord', 'middleware' => 'permission:organizations-list']);
    
    Route::post('powerpanel/department/get_builder_list', 'Powerpanel\Department\Controllers\Powerpanel\DepartmentController@get_buider_list');
});
