<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/service/', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@index')->name('powerpanel.service.list');
    Route::get('powerpanel/service/', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@index')->name('powerpanel.service.index');

    Route::post('powerpanel/service/get_list/', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@get_list')->name('powerpanel.service.get_list');
    Route::post('powerpanel/service/get_list_New/', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@get_list_New')->name('powerpanel.service.get_list_New');
    Route::post('powerpanel/service/get_list_draft/', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@get_list_draft')->name('powerpanel.service.get_list_draft');
    Route::post('powerpanel/service/get_list_trash/', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@get_list_trash')->name('powerpanel.service.get_list_trash');
    Route::post('powerpanel/service/get_list_favorite/', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@get_list_favorite')->name('powerpanel.service.get_list_favorite');

    Route::get('powerpanel/service/add/', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@edit')->name('powerpanel.service.add');
    Route::post('powerpanel/service/add/', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@handlePost')->name('powerpanel.service.add');

    Route::get('/powerpanel/service/{alias}/edit', ['as' => 'powerpanel.service.edit', 'uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@edit', 'middleware' => 'permission:service-edit']);
    Route::post('/powerpanel/service/{alias}/edit', ['as' => 'powerpanel.service.handleEditPost', 'uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@handlePost', 'middleware' => 'permission:service-edit']);

    Route::post('powerpanel/service/DeleteRecord', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@DeleteRecord');
    Route::post('powerpanel/service/publish', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@publish', 'middleware' => 'permission:service-edit']);
    Route::post('powerpanel/service/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@reorder', 'middleware' => 'permission:service-list']);
    Route::post('powerpanel/service/addpreview', ['as' => 'powerpanel.service.addpreview', 'uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@addPreview', 'middleware' => 'permission:service-create']);       
    Route::post('powerpanel/service/getChildData', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@getChildData', 'middleware' => 'permission:service-list']);
    Route::post('powerpanel/service/ApprovedData_Listing', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@ApprovedData_Listing', 'middleware' => 'permission:service-list']);
    Route::post('powerpanel/service/getChildData_rollback', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@getChildData_rollback']);
    Route::post('powerpanel/service/insertComents', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@insertComents']);
    Route::post('powerpanel/service/Get_Comments', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@Get_Comments']);
    Route::post('powerpanel/service/get_builder_list', 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@get_buider_list');
    Route::post('powerpanel/service/getCategory', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@getCategory']);
    Route::post('powerpanel/service/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@getSectorwiseCategoryGrid']);
    Route::post('powerpanel/service/getRegisterOfApplication', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@getRegisterOfApplication']);
    Route::post('powerpanel/service/getLicenceRegister', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@getLicenceRegister']);
    Route::post('powerpanel/service/rollback-record', ['uses' => 'Powerpanel\Service\Controllers\Powerpanel\ServiceController@rollBackRecord', 'middleware' => 'permission:service-list']);
});

Route::get('uploadServicesCSV', 'Powerpanel\Service\Controllers\ServiceCSVController@uploadCSV');