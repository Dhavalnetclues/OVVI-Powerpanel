<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/public-record/', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@index')->name('powerpanel.public-record.list');
    Route::get('powerpanel/public-record/', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@index')->name('powerpanel.public-record.index');
    
    Route::post('powerpanel/public-record/get_list/', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@get_list')->name('powerpanel.public-record.get_list');
    Route::post('powerpanel/public-record/get_list_New/', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@get_list_New')->name('powerpanel.public-record.get_list_New');
    Route::post('powerpanel/public-record/get_list_draft/', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@get_list_draft')->name('powerpanel.public-record.get_list_draft');
    Route::post('powerpanel/public-record/get_list_trash/', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@get_list_trash')->name('powerpanel.public-record.get_list_trash');
    Route::post('powerpanel/public-record/get_list_favorite/', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@get_list_favorite')->name('powerpanel.public-record.get_list_favorite');

    Route::get('powerpanel/public-record/add/', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@edit')->name('powerpanel.public-record.add');
    Route::post('powerpanel/public-record/add/', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@handlePost')->name('powerpanel.public-record.add');

    Route::get('/powerpanel/public-record/{alias}/edit', ['as' => 'powerpanel.public-record.edit', 'uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@edit', 'middleware' => 'permission:public-record-edit']);
    Route::post('/powerpanel/public-record/{alias}/edit', ['as' => 'powerpanel.public-record.handleEditPost', 'uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@handlePost', 'middleware' => 'permission:public-record-edit']);

    Route::post('powerpanel/public-record/DeleteRecord', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@DeleteRecord');
    Route::post('powerpanel/public-record/publish', ['uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@publish', 'middleware' => 'permission:public-record-edit']);
    Route::post('powerpanel/public-record/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@reorder', 'middleware' => 'permission:public-record-list']);
    Route::post('powerpanel/public-record/addpreview', ['as' => 'powerpanel.public-record.addpreview', 'uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@addPreview', 'middleware' => 'permission:public-record-create']);       
    Route::post('powerpanel/public-record/getChildData', ['uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@getChildData', 'middleware' => 'permission:public-record-list']);
    Route::post('powerpanel/public-record/ApprovedData_Listing', ['uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@ApprovedData_Listing', 'middleware' => 'permission:public-record-list']);
    Route::post('powerpanel/public-record/getChildData_rollback', ['uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@getChildData_rollback']);
    Route::post('powerpanel/public-record/insertComents', ['uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@insertComents']);
    Route::post('powerpanel/public-record/Get_Comments', ['uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@Get_Comments']);
    Route::post('powerpanel/public-record/get_builder_list', 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@get_buider_list');
    Route::post('powerpanel/public-record/getCategory', ['uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@getCategory']);
    Route::post('powerpanel/public-record/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@getSectorwiseCategoryGrid']);
    
    Route::post('powerpanel/public-record/rollback-record', ['uses' => 'Powerpanel\PublicRecord\Controllers\Powerpanel\PublicRecordController@rollBackRecord', 'middleware' => 'permission:public-record-list']);
});

Route::get('uploadPublicRecordCSV', 'Powerpanel\PublicRecord\Controllers\PublicRecordCSVController@uploadCSV');
