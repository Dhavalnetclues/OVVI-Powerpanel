<?php
Route::group(['middleware' => ['web', 'auth']], function() {
    Route::get('powerpanel/search-statictics/', 'Powerpanel\SearchStaticticsReport\Controllers\Powerpanel\SearchStaticticsController@index')->name('powerpanel.search-statictics.list');
    Route::get('powerpanel/search-statictics/', 'Powerpanel\SearchStaticticsReport\Controllers\Powerpanel\SearchStaticticsController@index')->name('powerpanel.search-statictics.index');
    
    Route::post('powerpanel/search-statictics/get_list/', 'Powerpanel\SearchStaticticsReport\Controllers\Powerpanel\SearchStaticticsController@get_list')->name('powerpanel.search-statictics.get_list');
   Route::get('/powerpanel/search-statictics/ExportRecord', ['uses' => 'Powerpanel\SearchStaticticsReport\Controllers\Powerpanel\SearchStaticticsController@ExportRecord', 'middleware' => 'permission:search-statictics-list']);
   Route::post('powerpanel/search-statictics/DeleteRecord', 'Powerpanel\SearchStaticticsReport\Controllers\Powerpanel\SearchStaticticsController@DeleteRecord');
       
});
