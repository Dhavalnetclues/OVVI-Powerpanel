<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/faq/', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@index')->name('powerpanel.faq.list');
    Route::get('powerpanel/faq/', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@index')->name('powerpanel.faq.index');
    
    Route::post('powerpanel/faq/get_list/', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@get_list')->name('powerpanel.faq.get_list');
    Route::post('powerpanel/faq/get_list_New/', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@get_list_New')->name('powerpanel.faq.get_list_New');
    Route::post('powerpanel/faq/get_list_draft/', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@get_list_draft')->name('powerpanel.faq.get_list_draft');
    Route::post('powerpanel/faq/get_list_trash/', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@get_list_trash')->name('powerpanel.faq.get_list_trash');
    Route::post('powerpanel/faq/get_list_favorite/', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@get_list_favorite')->name('powerpanel.faq.get_list_favorite');

    Route::get('powerpanel/faq/add/', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@edit')->name('powerpanel.faq.add');
    Route::post('powerpanel/faq/add/', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@handlePost')->name('powerpanel.faq.add');

    Route::get('/powerpanel/faq/{alias}/edit', ['as' => 'powerpanel.faq.edit', 'uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@edit', 'middleware' => 'permission:faq-edit']);
    Route::post('/powerpanel/faq/{alias}/edit', ['as' => 'powerpanel.faq.handleEditPost', 'uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@handlePost', 'middleware' => 'permission:faq-edit']);

    Route::post('powerpanel/faq/DeleteRecord', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@DeleteRecord');
    Route::post('powerpanel/faq/publish', ['uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@publish', 'middleware' => 'permission:faq-edit']);
    Route::post('powerpanel/faq/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@reorder', 'middleware' => 'permission:faq-list']);
       
    Route::post('powerpanel/faq/getChildData', ['uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@getChildData', 'middleware' => 'permission:faq-list']);
    Route::post('powerpanel/faq/ApprovedData_Listing', ['uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@ApprovedData_Listing', 'middleware' => 'permission:faq-list']);
    Route::post('powerpanel/faq/getChildData_rollback', ['uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@getChildData_rollback']);
    Route::post('powerpanel/faq/insertComents', ['uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@insertComents']);
    Route::post('powerpanel/faq/Get_Comments', ['uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@Get_Comments']);
    Route::post('powerpanel/faq/getCategory', ['uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@getCategory']);
    Route::post('powerpanel/faq/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@getSectorwiseCategoryGrid']);
    Route::post('powerpanel/faq/get_builder_list', 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@get_buider_list');
    Route::post('/powerpanel/faq/addpreview', ['as' => 'powerpanel.faq.addpreview', 'uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@addPreview', 'middleware' => 'permission:faq-create']);
    Route::post('powerpanel/faq/rollback-record', ['uses' => 'Powerpanel\Faq\Controllers\Powerpanel\FaqController@rollBackRecord', 'middleware' => 'permission:faq-list']);
});
