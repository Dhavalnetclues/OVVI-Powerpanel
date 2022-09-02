<?php

Route::group(['middleware' => ['web', 'auth']], function () {

    // User Routes

    Route::get('powerpanel/users', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@index')->name('powerpanel.users.index');

    Route::post('powerpanel/users/get_list/', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@get_list');

    Route::post('powerpanel/users/publish', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@publish', 'middleware' => 'permission:' . Config::get('Constant.MODULE.NAME') . '-edit']);

    Route::get('powerpanel/users/add', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@edit')->name('powerpanel.users.add');

    Route::post('powerpanel/users/add/', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@handlePost')->name('powerpanel.users.handleAddPost');

    Route::get('powerpanel/users/{alias}/edit', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@edit')->name('powerpanel.users.edit');

    Route::post('powerpanel/users/{alias}/edit', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@handlePost')->name('powerpanel/user/handleEditPost');

    Route::post('powerpanel/users/DeleteRecord', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@DeleteRecord');

    Route::post('/powerpanel/users/Security_Remove', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@Security_Remove'));

    Route::post('/powerpanel/users/Security_Add', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@Security_Add'));

    Route::post('/powerpanel/users/step_Email_Otp', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@step_Email_Otp'));

    Route::post('/powerpanel/users/step_otp_verify', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@step_Otp_verify'));

    Route::post('/powerpanel/users/user-locking', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\users\UserController@userLocking'));

    // Dashboard Routes

    Route::get('powerpanel', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@index');

    Route::get('/powerpanel/dashboard', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@index'])->name('dashboard.index');

    Route::post('/powerpanel/dashboard/ajax', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@ajaxcall']);

    Route::post('/powerpanel/dashboard/updateorder', ['as' => 'dashboard.updateorder', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@updateorder']);

    Route::post('/powerpanel/dashboard/updatedashboardsettings', ['as' => 'dashboard.updatedashboardsettings', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@updatedashboardsettings']);

    Route::post('/powerpanel/dashboard/Get_Comments_user', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@Get_Comments_user']);

    Route::post('/powerpanel/dashboard/InsertComments_user', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@InsertComments_user']);

    Route::post('/powerpanel/dashboard/doc-chart', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@getDocChartData']);

    Route::post('/powerpanel/dashboard/LeadChart', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@LeadChart']);

    Route::post('/powerpanel/dashboard/search-chart', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@SearchChart']);

    Route::post('/powerpanel/dashboard/mobilehist', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@getPageHitChart']);

    Route::get('/powerpanel/dashboard/widget-settings', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\dashboard\DashboardController@widgetSettings']);

    // Settings Routes
    Route::get('/powerpanel/settings', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\general_setting\SettingsController@index', 'middleware' => 'permission:settings-general-setting-management'])->name('powerpanel/settings');

    Route::post('/powerpanel/settings', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\general_setting\SettingsController@update_settings', 'middleware' => 'permission:settings-general-setting-management']);

    Route::get('/powerpanel/settings/getDBbackUp', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\general_setting\SettingsController@getDBbackUp'])->name('powerpanel/settings/getDBbackUp');

    Route::post('/settings/testMail', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\general_setting\SettingsController@testMail'])->name('settings/testMail');

    Route::post('/settings/save-module-settings', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\general_setting\SettingsController@saveModuleSettings'])->name('settings/save-module-settings');

    Route::post('/settings/get-save-module-settings', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\general_setting\SettingsController@getModuleSettings'])->name('settings/get-save-module-settings');

    Route::post('/settings/get-filtered-modules', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\general_setting\SettingsController@getModulesAjax'])->name('settings/get-filtered-modules');

    Route::post('/powerpanel/settings/insertticket', ['as' => 'powerpanel.settings.insertticket', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\general_setting\SettingsController@insertTicket']);

    Route::get('/powerpanel/settings/insertticket', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\general_setting\SettingsController@insertTicket']);

    //Profile Routes

    Route::get('/powerpanel/changeprofile', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\profile\ProfileController@index', 'middleware' => 'permission:changeprofile-edit'))->name('powerpanel/changeprofile');

    Route::post('/powerpanel/changeprofile', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\profile\ProfileController@changeprofile'));

    Route::get('/powerpanel/changepassword', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\profile\ProfileController@changepassword', 'middleware' => 'permission:changeprofile-change-password'))->name('powerpanel/changepassword');

    Route::post('/powerpanel/changepassword', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\profile\ProfileController@handle_changepassword'));

    //Email Log Routes

    Route::get('/powerpanel/email-log', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\email_log\EmailLogController@index'))->name('email_log');

    Route::post('/powerpanel/email-log/get_list', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\email_log\EmailLogController@get_list'));

    Route::post('/powerpanel/email-log/DeleteRecord', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\email_log\EmailLogController@DeleteRecord');

    //Error Log Routes

    Route::get('/powerpanel/error-logs', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\error_log\ErrorLogController@index'))->name('error_log');

    Route::post('/powerpanel/error-logs/get_list', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\error_log\ErrorLogController@get_list'));
    Route::get('/powerpanel/error-logs/view/{id}', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\error_log\ErrorLogController@view'))->name('error_log_view');

    Route::post('/powerpanel/error-log/DeleteRecord', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\error_log\ErrorLogController@DeleteRecord');

    //Logmanager

    Route::get('/powerpanel/log', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\log\LogController@index']);

    Route::post('/powerpanel/log/get_list', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\log\LogController@get_list']);

    Route::post('/powerpanel/log/selectRecords', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\log\LogController@selectRecords']);

    Route::get('/powerpanel/log/ExportRecord', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\log\LogController@ExportRecord']);

    Route::post('/powerpanel/log/DeleteRecord', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\log\LogController@DeleteRecord']);

    //Login History

    Route::get('/powerpanel/login-history', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\login_history\LoginHistoryController@index']);

    Route::post('/powerpanel/login-history/get_list', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\login_history\LoginHistoryController@get_list']);

    Route::post('/powerpanel/login-history/DeleteRecord', 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\login_history\LoginHistoryController@DeleteRecord');

    //Media Manager Module Routes

    Route::post('/powerpanel/media/set_image_html', ['as' => 'powerpanel/media/set_image_html', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@set_image_html']);

    Route::post('/powerpanel/media/ComposerDocData', ['as' => 'powerpanel/media/ComposerDocData', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@ComposerDocData']);

    Route::post('/powerpanel/media/ComposerDocDatajs', ['as' => 'powerpanel/media/ComposerDocDatajs', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@ComposerDocDatajs']);

    Route::post('/powerpanel/media/set_video_html', ['as' => 'powerpanel/media/set_video_html', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@set_video_html']);

    Route::post('/powerpanel/media/upload_image', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@upload_image'])->name('powerpanel/media/upload_image');

    Route::post('/powerpanel/media/upload_video', ['as' => 'powerpanel/media/upload_video', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@upload_video']);

    Route::post('/powerpanel/media/user_uploaded_video', ['as' => 'powerpanel/media/user_uploaded_video', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@user_uploaded_video']);

    Route::post('/powerpanel/media/get_trash_videos', ['as' => '/powerpanel/media/get_trash_videos', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@get_trash_videos']);

    Route::post('/powerpanel/media/user_uploaded_image', ['as' => '/powerpanel/media/user_uploaded_image', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@user_uploaded_image']);

    Route::post('/powerpanel/media/folder_uploaded_image', ['as' => '/powerpanel/media/folder_uploaded_image', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@folder_uploaded_image']);

    Route::post('/powerpanel/media/load_more_images/{user_id}', ['as' => '/powerpanel/media/load_more_images', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@load_more_images']);

    Route::post('/powerpanel/media/load_more_docs/{user_id}', ['as' => '/powerpanel/media/load_more_docs', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@load_more_docs']);

    Route::post('/powerpanel/media/remove_image', ['as' => '/powerpanel/media/remove_image', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@remove_image']);

    Route::post('/powerpanel/media/updateDocTitle', ['as' => '/powerpanel/media/updateDocTitle', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@updateDocTitle']);

    Route::post('/powerpanel/media/updateAudioTitle', ['as' => '/powerpanel/media/updateAudioTitle', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@updateAudioTitle']);

    Route::post('/powerpanel/media/get_recent_uploaded_images', ['as' => '/powerpanel/media/get_recent_uploaded_images', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@get_recent_uploaded_images']);

    Route::post('/powerpanel/media/get_trash_images', ['as' => '/powerpanel/media/get_trash_images', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@get_trash_images']);

    Route::post('/powerpanel/media/insert_image_by_url', ['as' => '/powerpanel/media/insert_image_by_url', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@insert_image_by_url']);

    Route::post('/powerpanel/media/insert_video_by_url', ['as' => '/powerpanel/media/insert_video_by_url', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@insert_video_by_url']);

    Route::post('/powerpanel/media/remove_multiple_image', ['as' => '/powerpanel/media/remove_multiple_image', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@remove_multiple_image']);

    Route::post('/powerpanel/media/remove_multiple_videos', ['as' => '/powerpanel/media/remove_multiple_videos', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@remove_multiple_videos']);

    Route::post('/powerpanel/media/restore_multiple_image', ['as' => '/powerpanel/media/restore_multiple_image', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@restore_multiple_image']);

    Route::post('/powerpanel/media/restore-multiple-videos', ['as' => '/powerpanel/media/restore-multiple-videos', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@restore_multiple_videos']);

    Route::post('/powerpanel/media/set_document_uploader', ['as' => 'powerpanel/media/set_document_uploader', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@set_document_uploader']);

    Route::post('/powerpanel/media/set_audio_uploader', ['as' => 'powerpanel/media/set_audio_uploader', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@set_audio_uploader']);

    Route::post('/powerpanel/media/upload_documents', ['as' => 'powerpanel/media/upload_documents', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@upload_documents']);

    Route::post('/powerpanel/media/upload_audios', ['as' => 'powerpanel/media/upload_audios', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@upload_audios']);

    Route::post('/powerpanel/media/user_uploaded_docs', ['as' => '/powerpanel/media/user_uploaded_docs', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@user_uploaded_docs']);

    Route::post('/powerpanel/media/folder_uploaded_docs', ['as' => '/powerpanel/media/folder_uploaded_docs', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@folder_uploaded_docs']);

    Route::post('/powerpanel/media/user_uploaded_audios', ['as' => '/powerpanel/media/user_uploaded_audios', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@user_uploaded_audios']);

    Route::post('/powerpanel/media/folder_uploaded_audios', ['as' => '/powerpanel/media/folder_uploaded_audios', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@folder_uploaded_audios']);

    Route::post('/powerpanel/media/remove_multiple_documents', ['as' => '/powerpanel/media/remove_multiple_documents', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@remove_multiple_documents']);

    Route::post('/powerpanel/media/remove_multiple_audios', ['as' => '/powerpanel/media/remove_multiple_audios', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@remove_multiple_audios']);

    Route::post('/powerpanel/media/get_trash_documents', ['as' => '/powerpanel/media/get_trash_documents', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@get_trash_documents']);

    Route::post('/powerpanel/media/get_trash_audios', ['as' => '/powerpanel/media/get_trash_audios', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@get_trash_audios']);

    Route::post('/powerpanel/media/get_trash_audios', ['as' => '/powerpanel/media/get_trash_audios', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@get_trash_audios']);

    Route::post('/powerpanel/media/check-img-inuse', ['as' => '/powerpanel/media/check-img-inuse', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@checkedUsedImg']);

    Route::post('/powerpanel/media/restore-multiple-document', ['as' => '/powerpanel/media/restore_multiple_document', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@restore_multiple_document']);

    Route::post('/powerpanel/media/restore-multiple-audio', ['as' => '/powerpanel/media/restore_multiple_audio', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@restore_multiple_audio']);

    Route::post('/powerpanel/media/check-document-inuse', ['as' => '/powerpanel/media/check-document-inuse', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@checkedUsedDocument']);

    Route::post('/powerpanel/media/check-audio-inuse', ['as' => '/powerpanel/media/check-audio-inuse', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@checkedUsedAudio']);

    Route::post('/powerpanel/media/get_image_details', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@getImageDetails'])->name('get_image_details');

    Route::post('/powerpanel/media/save_image_details', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@saveImageDetails'])->name('save_image_details');

    Route::post('/powerpanel/media/crop_image', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@cropImage'])->name('crop_image');

    Route::post('/powerpanel/media/save_cropped_image', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@saveCroppedImage'])->name('save_cropped_image');

    Route::get('/powerpanel/folderdata', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@folderdata'));

    Route::get('/powerpanel/FolderImages', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@GetFolderImages'));

    Route::get('/powerpanel/GetFolderDocument', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@GetFolderDocument'));

    Route::get('/powerpanel/GetFolderAudio', array('uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@GetFolderAudio'));

    Route::post('/powerpanel/media/empty_trash_Image', ['as' => 'powerpanel/media/empty_trash_image', 'uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\MediaController@empty_trash_image']);

    // Globalsearch Controller
    //Route::get('/powerpanel/search', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\GlobalSearchController@index']);
    Route::get('/powerpanel/search', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\GlobalSearchController@search']);
    Route::post('/powerpanel/search', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\GlobalSearchController@search']);
    Route::post('/powerpanel/search/auto-complete', ['uses' => 'Powerpanel\ShieldCMSTheme\Controllers\powerpanel\powerpanelcontroller\GlobalSearchController@autoComplete']);
    // Globalsearch Controller
});
