<?php 
Route::group(['middleware' => ['web', 'auth']], function() 
{
    Route::get('/powerpanel/menu', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@index', 'middleware' => ['permission:menu-list']]);
    Route::post('/powerpanel/menu/getMenuType', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@getMenuType', 'middleware' => ['permission:menu-list']]);
    Route::post('/powerpanel/menu/addMenuType', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@addMenuType', 'middleware' => ['permission:menu-create']]);
    Route::post('/powerpanel/menu/saveMenu', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@saveMenu', 'middleware' => ['permission:menu-create']]);
    Route::post('/powerpanel/menu/addMenuItem', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@addMenuItem', 'middleware' => ['permission:menu-create']]);
    Route::post('/powerpanel/menu/addMenuItems', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@addMenuItems', 'middleware' => ['permission:menu-create']]);
    Route::post('/powerpanel/menu/reload', 'Powerpanel\Menu\Controllers\powerpanel\MenuController@reload');
    Route::post('/powerpanel/menu/deleteMenuItem', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@deleteMenuItem', 'middleware' => ['permission:menu-delete']]);
    Route::post('/powerpanel/menu/deleteMenu', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@deleteMenu', 'middleware' => ['permission:menu-delete']]);
    Route::post('/powerpanel/menu/getMenuItem', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@getMenuItem', 'middleware' => ['permission:menu-edit']]);
    Route::post('/powerpanel/menu/updateMenuItem', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@updateMenuItem', 'middleware' => ['permission:menu-edit']]);
    Route::post('/powerpanel/menu/aliasGenerate', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@aliasGenerate'])->name('powerpanel/menu/aliasGenerate');
    Route::post('/powerpanel/menu/megaMenu', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@megaMenu', 'middleware' => ['permission:menu-edit']]);
    Route::post('/powerpanel/menu/getPageList', ['uses' => 'Powerpanel\Menu\Controllers\powerpanel\MenuController@getPageList']);

});