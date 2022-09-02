<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/hits-report/', 'Powerpanel\HitsReport\Controllers\Powerpanel\HitsReportController@index')->name('powerpanel.hits-report.list');
    Route::get('powerpanel/hits-report/', 'Powerpanel\HitsReport\Controllers\Powerpanel\HitsReportController@index')->name('powerpanel.hits-report.index');
    
    Route::post('powerpanel/hits-report/get_list/', 'Powerpanel\HitsReport\Controllers\Powerpanel\HitsReportController@get_list')->name('powerpanel.hits-report.get_list');
 Route::post('/powerpanel/hits-report/mobilehist', ['uses' => 'Powerpanel\HitsReport\Controllers\Powerpanel\HitsReportController@getPageHitChart']);   
  Route::post('/powerpanel/hits-report/sendreport', ['uses' => 'Powerpanel\HitsReport\Controllers\Powerpanel\HitsReportController@getSendChart']);
});
