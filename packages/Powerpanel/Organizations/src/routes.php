<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/organizations/', 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@index')->name('powerpanel.organizations.list');
    Route::get('powerpanel/organizations/', 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@index')->name('powerpanel.organizations.index');
    
    Route::post('powerpanel/organizations/get_list/', 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@get_list')->name('powerpanel.organizations.get_list');
    Route::post('powerpanel/organizations/get_list_New/', 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@get_list_New')->name('powerpanel.organizations.get_list_New');
   
    Route::get('powerpanel/organizations/add/', 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@edit')->name('powerpanel.organizations.add');
    Route::post('powerpanel/organizations/add/', 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@handlePost')->name('powerpanel.organizations.add');

    Route::get('/powerpanel/organizations/{alias}/edit', ['as' => 'powerpanel.organizations.edit', 'uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@edit', 'middleware' => 'permission:organizations-edit']);
    Route::post('/powerpanel/organizations/{alias}/edit', ['as' => 'powerpanel.organizations.handleEditPost', 'uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@handlePost', 'middleware' => 'permission:organizations-edit']);

    Route::post('powerpanel/organizations/DeleteRecord', 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@DeleteRecord');
    Route::post('powerpanel/organizations/publish', ['uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@publish', 'middleware' => 'permission:organizations-edit']);
    Route::post('powerpanel/organizations/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@reorder', 'middleware' => 'permission:organizations-list']);
       
    Route::post('powerpanel/organizations/getChildData', ['uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@getChildData', 'middleware' => 'permission:organizations-list']);
    Route::post('powerpanel/organizations/ApprovedData_Listing', ['uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@ApprovedData_Listing', 'middleware' => 'permission:organizations-list']);
    Route::post('powerpanel/organizations/getChildData_rollback', ['uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@getChildData_rollback']);
    Route::post('powerpanel/organizations/insertComents', ['uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@insertComents']);
    Route::post('powerpanel/organizations/Get_Comments', ['uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@Get_Comments']);

    Route::post('powerpanel/organizations/rollback-record', ['uses' => 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@rollBackRecord', 'middleware' => 'permission:organizations-list']);
    
    Route::post('powerpanel/organizations/get_builder_list', 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@get_buider_list');
    Route::post('powerpanel/organizations/getAllParents', 'Powerpanel\Organizations\Controllers\Powerpanel\OrganizationsController@getAllParents');
});
