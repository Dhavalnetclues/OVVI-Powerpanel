<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/companies', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@index')->name('powerpanel.companies.index');
    Route::post('powerpanel/companies/get_list/', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@get_list');
    Route::post('powerpanel/companies/get_list_New/', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@get_list_New');
    Route::post('powerpanel/companies/get_list_favorite/', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@get_list_favorite');
    Route::post('powerpanel/companies/get_list_draft/', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@get_list_draft');
    Route::post('powerpanel/companies/get_list_trash/', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@get_list_trash');

    Route::post('powerpanel/companies/publish', ['uses' => 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@publish', 'middleware' => 'permission:' . Config::get('Constant.MODULE.NAME') . '-edit']);

    Route::post('powerpanel/companies/reorder/', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@reorder')->name('powerpanel.companies.reorder');

    Route::post('powerpanel/companies/addpreview/', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@addPreview')->name('powerpanel.companies.addpreview');

    Route::get('powerpanel/companies/add', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@edit')->name('powerpanel.companies.add');
    Route::post('powerpanel/companies/add/', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@handlePost')->name('powerpanel.companies.handleAddPost');
    
    Route::get('powerpanel/companies/{alias}/edit', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@edit')->name('powerpanel.companies.edit');
    Route::post('powerpanel/companies/{alias}/edit', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@handlePost')->name('powerpanel/companies/handleEditPost');

    Route::post('powerpanel/companies/DeleteRecord', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@DeleteRecord');

    Route::post('powerpanel/companies/getChildData', ['uses' => 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@getChildData', 'middleware' => 'permission:companies-list']);
    Route::post('powerpanel/companies/ApprovedData_Listing', ['uses' => 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@ApprovedData_Listing', 'middleware' => 'permission:companies-list']);
    Route::post('powerpanel/companies/rollback-record', ['uses' => 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@rollBackRecord', 'middleware' => 'permission:companies-list']);

    Route::post('powerpanel/companies/getChildData_rollback', ['uses' => 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@getChildData_rollback']);
    
    Route::post('powerpanel/companies/insertComents', ['uses' => 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@insertComents']);
    Route::post('powerpanel/companies/Get_Comments', ['uses' => 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@Get_Comments']);
    
    Route::post('powerpanel/companies/get_builder_list', 'Powerpanel\Companies\Controllers\powerpanel\CompaniesController@get_buider_list');
});

Route::get('uploadCompaniesCSV', 'Powerpanel\Companies\Controllers\CompaniesCSVController@uploadCSV');
