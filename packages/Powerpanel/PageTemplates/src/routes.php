<?php

Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/page_template/', 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@index')->name('powerpanel.page_template.list');
    Route::get('powerpanel/page_template/', 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@index')->name('powerpanel.page_template.index');

    Route::post('powerpanel/page_template/get_list/', 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@get_list')->name('powerpanel.page_template.get_list');
    Route::get('powerpanel/page_template/add/', 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@edit')->name('powerpanel.page_template.add');
    Route::post('powerpanel/page_template/add/', 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@handlePost')->name('powerpanel.page_template.add');

    Route::get('/powerpanel/page_template/{alias}/edit', ['as' => 'powerpanel.page_template.edit', 'uses' => 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@edit', 'middleware' => 'permission:page_template-edit']);
    Route::post('/powerpanel/page_template/{alias}/edit', ['as' => 'powerpanel.page_template.handleEditPost', 'uses' => 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@handlePost', 'middleware' => 'permission:page_template-edit']);

    Route::post('powerpanel/page_template/DeleteRecord', 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@DeleteRecord');
    Route::post('powerpanel/page_template/publish', ['uses' => 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@publish', 'middleware' => 'permission:page_template-edit']);
    Route::post('powerpanel/page_template/reorder', ['as' => Config::get('Constant.MODULE.NAME') . '.reorder', 'uses' => 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@reorder', 'middleware' => 'permission:page_template-list']);
    Route::post('powerpanel/page_template/addpreview', ['as' => 'powerpanel.page_template.addpreview','uses' => 'Powerpanel\PageTemplates\Controllers\Powerpanel\PageTemplateController@addPreview']);             
});
