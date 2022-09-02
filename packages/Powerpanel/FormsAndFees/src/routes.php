<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/forms-and-fees/', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@index')->name('powerpanel.forms-and-fees.list');
    Route::get('powerpanel/forms-and-fees/', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@index')->name('powerpanel.forms-and-fees.index');
    
    Route::post('powerpanel/forms-and-fees/get_list/', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@get_list')->name('powerpanel.forms-and-fees.get_list');
    Route::post('powerpanel/forms-and-fees/get_list_New/', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@get_list_New')->name('powerpanel.forms-and-fees.get_list_New');
    Route::post('powerpanel/forms-and-fees/get_list_draft/', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@get_list_draft')->name('powerpanel.forms-and-fees.get_list_draft');
    Route::post('powerpanel/forms-and-fees/get_list_trash/', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@get_list_trash')->name('powerpanel.forms-and-fees.get_list_trash');
    Route::post('powerpanel/forms-and-fees/get_list_favorite/', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@get_list_favorite')->name('powerpanel.forms-and-fees.get_list_favorite');

    Route::get('powerpanel/forms-and-fees/add/', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@edit')->name('powerpanel.forms-and-fees.add');
    Route::post('powerpanel/forms-and-fees/add/', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@handlePost')->name('powerpanel.forms-and-fees.add');

    Route::get('/powerpanel/forms-and-fees/{alias}/edit', ['as' => 'powerpanel.forms-and-fees.edit', 'uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@edit', 'middleware' => 'permission:forms-and-fees-edit']);
    Route::post('/powerpanel/forms-and-fees/{alias}/edit', ['as' => 'powerpanel.forms-and-fees.handleEditPost', 'uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@handlePost', 'middleware' => 'permission:forms-and-fees-edit']);

    Route::post('powerpanel/forms-and-fees/DeleteRecord', 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@DeleteRecord');
    Route::post('powerpanel/forms-and-fees/publish', ['uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@publish', 'middleware' => 'permission:forms-and-fees-edit']);
    Route::post('powerpanel/forms-and-fees/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@reorder', 'middleware' => 'permission:forms-and-fees-list']);
    Route::post('powerpanel/forms-and-fees/addpreview', ['as' => 'powerpanel.forms-and-fees.addpreview', 'uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@addPreview', 'middleware' => 'permission:forms-and-fees-create']);             
    Route::post('powerpanel/forms-and-fees/getChildData', ['uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@getChildData', 'middleware' => 'permission:forms-and-fees-list']);
    Route::post('powerpanel/forms-and-fees/ApprovedData_Listing', ['uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@ApprovedData_Listing', 'middleware' => 'permission:forms-and-fees-list']);
    Route::post('powerpanel/forms-and-fees/getChildData_rollback', ['uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@getChildData_rollback']);
    Route::post('powerpanel/forms-and-fees/insertComents', ['uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@insertComents']);
    Route::post('powerpanel/forms-and-fees/Get_Comments', ['uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@Get_Comments']);
    Route::post('powerpanel/forms-and-fees/rollback-record', ['uses' => 'Powerpanel\FormsAndFees\Controllers\Powerpanel\FormsAndFeesController@rollBackRecord', 'middleware' => 'permission:forms-and-fees-list']);
    Route::post('powerpanel/forms-and-fees/get_builder_list', 'Powerpanel\FormsAndFees\Controllers\powerpanel\FormsAndFeesController@get_buider_list');
    
});
