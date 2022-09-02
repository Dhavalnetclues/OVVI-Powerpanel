<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/licence-register/', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@index')->name('powerpanel.licence-register.list');
    Route::get('powerpanel/licence-register/', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@index')->name('powerpanel.licence-register.index');
    
    Route::post('powerpanel/licence-register/get_list/', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@get_list')->name('powerpanel.licence-register.get_list');
    Route::post('powerpanel/licence-register/get_list_New/', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@get_list_New')->name('powerpanel.licence-register.get_list_New');
    Route::post('powerpanel/licence-register/get_list_draft/', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@get_list_draft')->name('powerpanel.licence-register.get_list_draft');
    Route::post('powerpanel/licence-register/get_list_trash/', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@get_list_trash')->name('powerpanel.licence-register.get_list_trash');
    Route::post('powerpanel/licence-register/get_list_favorite/', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@get_list_favorite')->name('powerpanel.licence-register.get_list_favorite');

    Route::get('powerpanel/licence-register/add/', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@edit')->name('powerpanel.licence-register.add');
    Route::post('powerpanel/licence-register/add/', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@handlePost')->name('powerpanel.licence-register.add');

    Route::get('/powerpanel/licence-register/{alias}/edit', ['as' => 'powerpanel.licence-register.edit', 'uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@edit', 'middleware' => 'permission:licence-register-edit']);
    Route::post('/powerpanel/licence-register/{alias}/edit', ['as' => 'powerpanel.licence-register.handleEditPost', 'uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@handlePost', 'middleware' => 'permission:licence-register-edit']);

    Route::post('powerpanel/licence-register/DeleteRecord', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@DeleteRecord');
    Route::post('powerpanel/licence-register/publish', ['uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@publish', 'middleware' => 'permission:licence-register-edit']);
    Route::post('powerpanel/licence-register/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@reorder', 'middleware' => 'permission:licence-register-list']);
    Route::post('powerpanel/licence-register/addpreview', ['as' => 'powerpanel.licence-register.addpreview', 'uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@addPreview', 'middleware' => 'permission:licence-register-create']);             
    Route::post('powerpanel/licence-register/getChildData', ['uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@getChildData', 'middleware' => 'permission:licence-register-list']);
    Route::post('powerpanel/licence-register/ApprovedData_Listing', ['uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@ApprovedData_Listing', 'middleware' => 'permission:licence-register-list']);
    Route::post('powerpanel/licence-register/getChildData_rollback', ['uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@getChildData_rollback']);
    Route::post('powerpanel/licence-register/insertComents', ['uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@insertComents']);
    Route::post('powerpanel/licence-register/Get_Comments', ['uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@Get_Comments']);
    Route::post('powerpanel/licence-register/rollback-record', ['uses' => 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@rollBackRecord', 'middleware' => 'permission:licence-register-list']);
    Route::post('powerpanel/licence-register/get_builder_list', 'Powerpanel\LicenceRegister\Controllers\Powerpanel\LicenceRegisterController@get_buider_list');
    
});

Route::get('uploadLicenceRegisterCSV', 'Powerpanel\LicenceRegister\Controllers\LicenceRegisterCSVController@uploadCSV');
Route::get('uploadLicenceRegisterDocumentCSV', 'Powerpanel\LicenceRegister\Controllers\LicenceRegisterCSVController@uploadLicenceRegisterDocumentCSV');
