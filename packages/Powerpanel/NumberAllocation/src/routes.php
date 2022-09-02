<?php
//echo Config::get('Constant.MODULE.NAME');exit;
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/number-allocation', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@index')->name('powerpanel.number-allocation.index');
    Route::post('powerpanel/number-allocation/get_list/', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@get_list');
    Route::post('powerpanel/number-allocation/get_list_New/', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@get_list_New');
    Route::post('powerpanel/number-allocation/get_list_favorite/', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@get_list_favorite');
    Route::post('powerpanel/number-allocation/get_list_draft/', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@get_list_draft');
    Route::post('powerpanel/number-allocation/get_list_trash/', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@get_list_trash');

    Route::post('powerpanel/number-allocation/publish', ['uses' => 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@publish', 'middleware' => 'permission:number-allocation-edit']);

    Route::post('powerpanel/number-allocation/reorder/', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@reorder')->name('powerpanel.number-allocation.reorder');

    Route::post('powerpanel/number-allocation/addpreview/', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@addPreview')->name('powerpanel.number-allocation.addpreview');

    Route::get('powerpanel/number-allocation/add', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@edit')->name('powerpanel.number-allocation.add');
    Route::post('powerpanel/number-allocation/add/', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@handlePost')->name('powerpanel.number-allocation.handleAddPost');
    
    Route::get('powerpanel/number-allocation/{alias}/edit', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@edit')->name('powerpanel.number-allocation.edit');
    Route::post('powerpanel/number-allocation/{alias}/edit', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@handlePost')->name('powerpanel/number-allocation/handleEditPost');

    Route::post('powerpanel/number-allocation/DeleteRecord', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@DeleteRecord');

    Route::post('powerpanel/number-allocation/getChildData', ['uses' => 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@getChildData', 'middleware' => 'permission:number-allocation-list']);
    Route::post('powerpanel/number-allocation/ApprovedData_Listing', ['uses' => 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@ApprovedData_Listing', 'middleware' => 'permission:number-allocation-list']);
    Route::post('powerpanel/number-allocation/rollback-record', ['uses' => 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@rollBackRecord', 'middleware' => 'permission:number-allocation-list']);

    Route::post('powerpanel/number-allocation/getChildData_rollback', ['uses' => 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@getChildData_rollback']);
    
    Route::post('powerpanel/number-allocation/insertComents', ['uses' => 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@insertComents']);
    Route::post('powerpanel/number-allocation/Get_Comments', ['uses' => 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@Get_Comments']);
    
    Route::post('powerpanel/number-allocation/get_builder_list', 'Powerpanel\NumberAllocation\Controllers\powerpanel\NumberAllocationController@get_buider_list');
});
