<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/consultations/', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@index')->name('powerpanel.consultations.list');
    Route::get('powerpanel/consultations/', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@index')->name('powerpanel.consultations.index');
    
    Route::post('powerpanel/consultations/get_list/', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@get_list')->name('powerpanel.consultations.get_list');
    Route::post('powerpanel/consultations/get_list_New/', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@get_list_New')->name('powerpanel.consultations.get_list_New');
    Route::post('powerpanel/consultations/get_list_draft/', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@get_list_draft')->name('powerpanel.consultations.get_list_draft');
    Route::post('powerpanel/consultations/get_list_trash/', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@get_list_trash')->name('powerpanel.consultations.get_list_trash');
    Route::post('powerpanel/consultations/get_list_favorite/', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@get_list_favorite')->name('powerpanel.consultations.get_list_favorite');

    Route::get('powerpanel/consultations/add/', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@edit')->name('powerpanel.consultations.add');
    Route::post('powerpanel/consultations/add/', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@handlePost')->name('powerpanel.consultations.add');

    Route::get('/powerpanel/consultations/{alias}/edit', ['as' => 'powerpanel.consultations.edit', 'uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@edit', 'middleware' => 'permission:consultations-edit']);
    Route::post('/powerpanel/consultations/{alias}/edit', ['as' => 'powerpanel.consultations.handleEditPost', 'uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@handlePost', 'middleware' => 'permission:consultations-edit']);

    Route::post('powerpanel/consultations/DeleteRecord', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@DeleteRecord');
    
    Route::post('powerpanel/consultations/publish', ['uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@publish', 'middleware' => 'permission:consultations-edit']);
    Route::post('powerpanel/consultations/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@reorder', 'middleware' => 'permission:consultations-list']);
    Route::post('powerpanel/consultations/addpreview', ['as' => 'powerpanel.consultations.addpreview', 'uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@addPreview', 'middleware' => 'permission:consultations-create']);       
    Route::post('powerpanel/consultations/getChildData', ['uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@getChildData', 'middleware' => 'permission:consultations-list']);
    Route::post('powerpanel/consultations/ApprovedData_Listing', ['uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@ApprovedData_Listing', 'middleware' => 'permission:consultations-list']);
    Route::post('powerpanel/consultations/getChildData_rollback', ['uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@getChildData_rollback']);
    Route::post('powerpanel/consultations/insertComents', ['uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@insertComents']);
    Route::post('powerpanel/consultations/Get_Comments', ['uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@Get_Comments']);
    Route::post('powerpanel/consultations/get_builder_list', 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@get_buider_list');

    Route::post('powerpanel/consultations/rollback-record', ['uses' => 'Powerpanel\Consultations\Controllers\Powerpanel\ConsultationsController@rollBackRecord', 'middleware' => 'permission:consultations-list']);
});
