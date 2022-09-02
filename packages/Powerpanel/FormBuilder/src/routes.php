<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/formbuilder/', 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@index')->name('powerpanel.formbuilder.list');
    Route::get('powerpanel/formbuilder/', 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@index')->name('powerpanel.formbuilder.index');
     Route::get('powerpanel/formbuilder/add/', 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@edit')->name('powerpanel.formbuilder.add');
    Route::post('powerpanel/formbuilder/add/', 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@handlePost')->name('powerpanel.formbuilder.add');
  Route::get('/powerpanel/formbuilder/{alias}/edit', ['as' => 'powerpanel.formbuilder.edit', 'uses' => 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@edit', 'middleware' => 'permission:formbuilder-edit']);
    Route::post('/powerpanel/formbuilder/{alias}/edit', ['as' => 'powerpanel.formbuilder.handleEditPost', 'uses' => 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@handlePost', 'middleware' => 'permission:formbuilder-edit']);

    Route::post('powerpanel/formbuilder/get_list/', 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@get_list')->name('powerpanel.formbuilder.get_list');
    Route::post('/powerpanel/formbuilder/formbuilderdata', array('uses' => 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@GetFormBuilderData'));
    Route::post('/powerpanel/formbuilder/updateformbuilderdata', array('uses' => 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@GetUpdateFormBuilderData'));
    Route::post('/powerpanel/formbuilder/DeleteRecord', array('uses' => 'Powerpanel\FormBuilder\Controllers\Powerpanel\FormBuilderController@DeleteRecord'));
});
