<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/formbuilder-lead/', 'Powerpanel\FormBuilderLead\Controllers\Powerpanel\FormBuilderLeadController@index')->name('powerpanel.formbuilder-lead-list.list');
    Route::get('powerpanel/formbuilder-lead/', 'Powerpanel\FormBuilderLead\Controllers\Powerpanel\FormBuilderLeadController@index')->name('powerpanel.formbuilder-lead-list.index');
    Route::post('powerpanel/formbuilder-lead/get_list', 'Powerpanel\FormBuilderLead\Controllers\Powerpanel\FormBuilderLeadController@get_list')->name('powerpanel.formbuilder-lead-list.get_list');
    Route::get('powerpanel/formbuilder-lead/ExportRecord', ['uses' => 'Powerpanel\FormBuilderLead\Controllers\Powerpanel\FormBuilderLeadController@ExportRecord']);
    Route::post('powerpanel/formbuilder-lead/DeleteRecord', array('uses' => 'Powerpanel\FormBuilderLead\Controllers\Powerpanel\FormBuilderLeadController@DeleteRecord'));
});
