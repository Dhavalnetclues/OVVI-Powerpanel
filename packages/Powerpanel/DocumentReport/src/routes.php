<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/document-report/', 'Powerpanel\DocumentReport\Controllers\Powerpanel\DocumentReportController@index')->name('powerpanel.document-report.list');
    Route::get('powerpanel/document-report/', 'Powerpanel\DocumentReport\Controllers\Powerpanel\DocumentReportController@index')->name('powerpanel.document-report.index');
    Route::post('powerpanel/document-report/get_list/', 'Powerpanel\DocumentReport\Controllers\Powerpanel\DocumentReportController@get_list')->name('powerpanel.document-report.get_list');
    Route::post('/powerpanel/document-report/doc-chart', ['uses' => 'Powerpanel\DocumentReport\Controllers\Powerpanel\DocumentReportController@getDocChart']);
    Route::post('/powerpanel/document-report/sendreport', ['uses' => 'Powerpanel\DocumentReport\Controllers\Powerpanel\DocumentReportController@getSendChart']);
});
