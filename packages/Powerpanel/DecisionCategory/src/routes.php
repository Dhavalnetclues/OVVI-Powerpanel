<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/decision-category/', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@index')->name('powerpanel.decision-category.list');
    Route::get('powerpanel/decision-category/', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@index')->name('powerpanel.decision-category.index');
    
    Route::post('powerpanel/decision-category/get_list/', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@get_list')->name('powerpanel.decision-category.get_list');
    Route::post('powerpanel/decision-category/get_list_New/', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@get_list_New')->name('powerpanel.decision-category.get_list_New');
    Route::post('powerpanel/decision-category/get_list_draft/', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@get_list_draft')->name('powerpanel.decision-category.get_list_draft');
    Route::post('powerpanel/decision-category/get_list_trash/', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@get_list_trash')->name('powerpanel.decision-category.get_list_trash');
    Route::post('powerpanel/decision-category/get_list_favorite/', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@get_list_favorite')->name('powerpanel.decision-category.get_list_favorite');

    Route::get('powerpanel/decision-category/add/', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@edit')->name('powerpanel.decision-category.add');
    Route::post('powerpanel/decision-category/add/', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@handlePost')->name('powerpanel.decision-category.add');

    Route::get('/powerpanel/decision-category/{alias}/edit', ['as' => 'powerpanel.decision-category.edit', 'uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@edit', 'middleware' => 'permission:decision-category-edit']);
    Route::post('/powerpanel/decision-category/{alias}/edit', ['as' => 'powerpanel.decision-category.handleEditPost', 'uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@handlePost', 'middleware' => 'permission:decision-category-edit']);

    Route::post('powerpanel/decision-category/DeleteRecord', 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@DeleteRecord');
    Route::post('powerpanel/decision-category/publish', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@publish', 'middleware' => 'permission:decision-category-edit']);
    Route::post('powerpanel/decision-category/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@reorder', 'middleware' => 'permission:decision-category-list']);
    Route::post('powerpanel/decision-category/addpreview', ['as' => 'powerpanel.decision-category.addpreview', 'uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@addPreview', 'middleware' => 'permission:decision-category-create']);       
    Route::post('powerpanel/decision-category/getChildData', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@getChildData', 'middleware' => 'permission:decision-category-list']);
    Route::post('powerpanel/decision-category/ApprovedData_Listing', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@ApprovedData_Listing', 'middleware' => 'permission:decision-category-list']);
    Route::post('powerpanel/decision-category/getChildData_rollback', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@getChildData_rollback']);
    Route::post('powerpanel/decision-category/insertComents', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@insertComents']);
    Route::post('powerpanel/decision-category/Get_Comments', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@Get_Comments']);
    
     Route::post('powerpanel/decision-category/get_builder_list', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@get_builder_list']);
    Route::post('powerpanel/decision-category/getAllCategory', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@getAllCategory']);
    Route::post('powerpanel/decision-category/getSectorwiseCategoryGrid', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@getSectorwiseCategoryGrid']);

    Route::post('powerpanel/decision-category/rollback-record', ['uses' => 'Powerpanel\DecisionCategory\Controllers\Powerpanel\DecisionCategoryController@rollBackRecord', 'middleware' => 'permission:decision-category-list']);
});
