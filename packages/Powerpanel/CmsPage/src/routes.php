<?php

Route::get('dnd-panel', function(){
	return view('cmspage::dnd');
});

Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/pages/', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@index')->name('powerpanel.pages.list');
    Route::get('powerpanel/pages/', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@index')->name('powerpanel.pages.index');
    
    Route::post('powerpanel/pages/get_list/', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@get_list')->name('powerpanel.pages.get_list');
    Route::post('powerpanel/pages/get_list_New/', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@get_list_New')->name('powerpanel.pages.get_list_New');
    Route::post('powerpanel/pages/get_list_draft/', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@get_list_draft')->name('powerpanel.pages.get_list_draft');
    Route::post('powerpanel/pages/get_list_trash/', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@get_list_trash')->name('powerpanel.pages.get_list_trash');
    Route::post('powerpanel/pages/get_list_favorite/', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@get_list_favorite')->name('powerpanel.pages.get_list_favorite');

    Route::get('powerpanel/pages/add/', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@edit')->name('powerpanel.pages.add');
    Route::post('powerpanel/pages/add/', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@handlePost');
    
    Route::get('powerpanel/pages/{alias}/edit', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@edit', 'middleware' => 'permission:pages-edit'])->name('powerpanel.pages.edit');
    Route::post('powerpanel/pages/{alias}/edit', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@handlePost', 'middleware' => 'permission:pages-edit'])->name('powerpanel.pages.handleEditPost');
    Route::post('powerpanel/pages/sharepage', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@sharePage', 'middleware' => 'permission:pages-edit'])->name('powerpanel.pages.sharepage');

    Route::post('powerpanel/pages/DeleteRecord', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@DeleteRecord');
    Route::post('powerpanel/pages/Template_Listing', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@Template_Listing');
    Route::post('powerpanel/pages/FormBuilder_Listing', 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@FormBuilder_Listing');
    Route::post('powerpanel/pages/publish', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@publish', 'middleware' => 'permission:pages-edit']);
    Route::post('powerpanel/pages/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@reorder', 'middleware' => 'permission:pages-list']);
    Route::post('powerpanel/pages/addpreview', ['as' => 'powerpanel.pages.addpreview', 'uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@addPreview', 'middleware' => 'permission:pages-create']);             
    Route::post('powerpanel/pages/getChildData', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@getChildData', 'middleware' => 'permission:pages-list']);
    Route::post('powerpanel/pages/ApprovedData_Listing', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@ApprovedData_Listing', 'middleware' => 'permission:pages-list']);
    Route::post('powerpanel/pages/getChildData_rollback', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@getChildData_rollback']);
    Route::post('powerpanel/pages/insertComents', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@insertComents']);
    Route::post('powerpanel/pages/Get_Comments', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@Get_Comments']);

    Route::post('powerpanel/pages/rollback-record', ['uses' => 'Powerpanel\CmsPage\Controllers\Powerpanel\CmsPagesController@rollBackRecord', 'middleware' => 'permission:pages-list']);
});
