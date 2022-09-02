<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/faq-category/', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@index')->name('powerpanel.faq-category.list');
    Route::get('powerpanel/faq-category/', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@index')->name('powerpanel.faq-category.index');
    
    Route::post('powerpanel/faq-category/get_list/', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@get_list')->name('powerpanel.faq-category.get_list');
    Route::post('powerpanel/faq-category/get_list_New/', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@get_list_New')->name('powerpanel.faq-category.get_list_New');
    Route::post('powerpanel/faq-category/get_list_draft/', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@get_list_draft')->name('powerpanel.faq-category.get_list_draft');
    Route::post('powerpanel/faq-category/get_list_trash/', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@get_list_trash')->name('powerpanel.faq-category.get_list_trash');
    Route::post('powerpanel/faq-category/get_list_favorite/', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@get_list_favorite')->name('powerpanel.faq-category.get_list_favorite');

    Route::get('powerpanel/faq-category/add/', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@edit')->name('powerpanel.faq-category.add');
    Route::post('powerpanel/faq-category/add/', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@handlePost')->name('powerpanel.faq-category.add');

    Route::get('/powerpanel/faq-category/{alias}/edit', ['as' => 'powerpanel.faq-category.edit', 'uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@edit', 'middleware' => 'permission:faq-category-edit']);
    Route::post('/powerpanel/faq-category/{alias}/edit', ['as' => 'powerpanel.faq-category.handleEditPost', 'uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@handlePost', 'middleware' => 'permission:faq-category-edit']);

    Route::post('powerpanel/faq-category/DeleteRecord', 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@DeleteRecord');
    Route::post('powerpanel/faq-category/publish', ['uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@publish', 'middleware' => 'permission:faq-category-edit']);
    Route::post('powerpanel/faq-category/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@reorder', 'middleware' => 'permission:faq-category-list']);
       
    Route::post('powerpanel/faq-category/getChildData', ['uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@getChildData', 'middleware' => 'permission:faq-category-list']);
    Route::post('powerpanel/faq-category/ApprovedData_Listing', ['uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@ApprovedData_Listing', 'middleware' => 'permission:faq-category-list']);
    Route::post('powerpanel/faq-category/getChildData_rollback', ['uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@getChildData_rollback']);
    Route::post('powerpanel/faq-category/insertComents', ['uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@insertComents']);
    Route::post('powerpanel/faq-category/Get_Comments', ['uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@Get_Comments']);
    Route::post('powerpanel/faq-category/addpreview', ['as' => 'powerpanel.faq-category.addpreview', 'uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@addPreview', 'middleware' => 'permission:faq-category-create']);
    Route::post('powerpanel/faq-category/rollback-record', ['uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@rollBackRecord', 'middleware' => 'permission:faq-category-list']);
    Route::post('powerpanel/faq-category/get_builder_list', ['uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@get_builder_list']);
    Route::post('powerpanel/faq-category/getAllCategory', ['uses' => 'Powerpanel\FaqCategory\Controllers\Powerpanel\FaqCategoryController@getAllCategory']);
});
