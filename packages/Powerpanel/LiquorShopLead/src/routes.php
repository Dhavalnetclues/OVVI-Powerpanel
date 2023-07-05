<?php
Route::group(['middleware' => ['web', 'auth']], function () {
    Route::get('powerpanel/liquorshop-lead/', 'Powerpanel\LiquorShopLead\Controllers\Powerpanel\LiquorShopLeadController@index')->name('powerpanel.liquorshop-lead.list');
    Route::get('powerpanel/liquorshop-lead/', 'Powerpanel\LiquorShopLead\Controllers\Powerpanel\LiquorShopLeadController@index')->name('powerpanel.liquorshop-lead.index');

    Route::post('powerpanel/liquorshop-lead/get_list/', 'Powerpanel\LiquorShopLead\Controllers\Powerpanel\LiquorShopLeadController@get_list')->name('powerpanel.liquorshop-lead.get_list');
    Route::get('/powerpanel/liquorshop-lead/ExportRecord', ['uses' => 'Powerpanel\LiquorShopLead\Controllers\Powerpanel\LiquorShopLeadController@ExportRecord', 'middleware' => 'permission:liquorshoplead-list']);
    Route::post('powerpanel/liquorshop-lead/DeleteRecord', 'Powerpanel\LiquorShopLead\Controllers\Powerpanel\LiquorShopLeadController@DeleteRecord');

    Route::post('/powerpanel/liquorshop-lead/emailreply', ['uses' => 'Powerpanel\LiquorShopLead\Controllers\Powerpanel\LiquorShopLeadController@emailreply']);
    Route::post('/powerpanel/liquorshop-lead/emailforword', ['uses' => 'Powerpanel\LiquorShopLead\Controllers\Powerpanel\LiquorShopLeadController@emailforword']);

});
