<?php
if (File::exists(app_path() . '/Helpers/MyLibrary.php') && Schema::hasTable('module')) {

    // if (Schema::hasTable('live_user')) {
    //     $ip = App\Helpers\MyLibrary::get_client_ip();
    //     $Block_live_user = \Powerpanel\LiveUser\Models\LiveUsers::getRecordCountByIp($ip);
    //     if ($Block_live_user >= 1) {
    //         $message = 'YOU ARE NOT AUTHORIZED TO ACCESS THIS WEB PAGE';
    //         echo view('errors.authorised', compact('message'))->render();
    //         exit();
    //     }
    // }

    // if (Schema::hasTable('general_setting')) {
    //     if (Request::segment(1) == 'powerpanel') {
    //         $ip = App\Helpers\MyLibrary::get_client_ip();
    //         $arrSettings = \App\GeneralSettings::getSettingsByFieldName('IP_SETTING');
    //         if (!empty($arrSettings['fieldValue'])) {
    //             $allow = explode(",", $arrSettings['fieldValue']); //allowed IPs
    //             if (!in_array($ip, $allow)) {
    //                 $message = 'YOU ARE NOT AUTHORIZED TO ACCESS THIS WEB PAGE';
    //                 echo view('errors.authorised', compact('message'))->render();
    //                 exit();
    //             }
    //         }
    //     }
    // }

    // if (Schema::hasTable('blocked_ips')) {
    //     if (Request::segment(1) == 'powerpanel') {
    //         $MAX_LOGIN_ATTEMPTS = 5;
    //         if (Schema::hasTable('general_setting')) {
    //             $genSetting = DB::table('general_setting')->select('fieldValue')->where('fieldName', 'MAX_LOGIN_ATTEMPTS')->first();
    //             $MAX_LOGIN_ATTEMPTS = (int) $genSetting->fieldValue;
    //         }

    //         $ip = App\Helpers\MyLibrary::get_client_ip();
    //         $ipCount = \Powerpanel\BlockedIP\Models\BlockedIps::getRecordCountByIp($ip);
    //         if ($ipCount >= $MAX_LOGIN_ATTEMPTS) {
    //             $message = 'This IP has been blocked due to too many login attempts!<br> Please Contact administrator for further assistance.';
    //             echo view('errors.attempts', compact('message'))->render();
    //             exit();
    //         }
    //     }
    // }

    if (Request::segment(1) == 'front-html') {
        Route::get('/front-html/{pagename}', 'Powerpanel\CmsPage\Controllers\CmsPagesController@checkCmsPageDesign');
    } else {

        $segmentArr = [];
        $segmentArr = Request::segments();
        $preview = Request::segment(3);
        if ($preview != 'preview') {
            $preview = false;
        }

        $setConstants = App\Helpers\MyLibrary::setConstants($segmentArr, $preview);
        
        $CONTROLLER_NAME_SPACE = Config::get('Constant.MODULE.CONTROLLER_NAME_SPACE');

        $segment1 = Request::segment(1);

        if (!empty($segment1) && $segment1 != 'powerpanel') {

            $segment2 = Request::segment(2);
            $sector = false;
            $sector_slug = '';
            if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy" || $segment1 == "spectrum") && (!empty($segment2))) {
                $sector = true;
            }

            if ($sector) {
                $sector_slug = Request::segment(1);
                $slug = Request::segment(2);
                $currentURL = $sector_slug . '/' . $slug;
            } else {
                $slug = Request::segment(1);
                $currentURL = $slug;
            }

            $preview = Request::segment(3);
            if ($preview != 'preview') {
                $preview = false;
            }

            $arrModule = App\Helpers\MyLibrary::setFrontRoutes($slug, $preview, $sector_slug);
            
            $MODULE_NAME = Config::get('Constant.MODULE.NAME');
            $CONTROLLER_NAME = Config::get('Constant.MODULE.CONTROLLER');

            if (isset($arrModule->modules->varModuleName)) {
                switch ($arrModule->modules->varModuleName) {
                    case 'contact-us':

                        Route::get('contact-us/thankyou', ['as' => 'contact-us/thankyou', 'uses' => 'ThankyouController@index']);
                        Route::get('/' . $arrModule->alias->varAlias, ['as' => 'contact-us', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@create']);
                        Route::post('/' . $arrModule->alias->varAlias, ['as' => 'contact-us', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@store']);
                        Route::get($arrModule->alias->varAlias . '/{alias}/preview', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@create']);
                        break;
                    case 'online-polling':
                        
                        Route::get('online-polling/thankyou', ['as' => 'online-polling/thankyou', 'uses' => 'ThankyouController@index']);
                        Route::get('/'.$arrModule->alias->varAlias, ['as' => 'online-polling', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                        if ($sector) {
                            Route::any($sector_slug . '/' . $arrModule->alias->varAlias, ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                        }
                        Route::post('/'.$arrModule->alias->varAlias, ['as' => 'online-polling', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@store']);            

                    break;
                    case "events":
                        
                        Route::get($arrModule->alias->varAlias, ['as' => 'events', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                        Route::post($arrModule->alias->varAlias, ['as' => 'events', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@store']);

                        Route::any($sector_slug . '/' . $arrModule->alias->varAlias, ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                        Route::post($sector_slug . '/' . $arrModule->alias->varAlias . '/fetchdata', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@fetchData']);
                        Route::post($arrModule->alias->varAlias . '/fetchdata', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@fetchData']);
                         Route::post($sector_slug . '/' . $arrModule->alias->varAlias . '/getCategorySector', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@getCategorySector']);
                        Route::post($arrModule->alias->varAlias . '/getCategorySector', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@getCategorySector']);
                        Route::get($arrModule->alias->varAlias . '/{alias}', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);
                        Route::post($arrModule->alias->varAlias . '/{alias}', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@store']);
                        Route::get($arrModule->alias->varAlias . '/{alias}/preview', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                        Route::get($arrModule->alias->varAlias . '/{alias}/preview/detail', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);
                        break;
//                    case "careers":
//                        Route::get($arrModule->alias->varAlias, ['as' => 'careers', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
//                        Route::post($arrModule->alias->varAlias, ['as' => 'careers', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@store']);
//                        Route::post($arrModule->alias->varAlias . '/fetchdata', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@fetchData']);
//                        Route::get($arrModule->alias->varAlias . '/{alias}', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);
//                        Route::post($arrModule->alias->varAlias . '/{alias}', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@store']);
//                        Route::get($arrModule->alias->varAlias . '/{alias}/preview', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
//                        Route::get($arrModule->alias->varAlias . '/{alias}/preview/detail', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);
//                        break;
                    case "payonline":
                        
                        Route::get($arrModule->alias->varAlias, ['as' => 'payonline', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                        Route::post($arrModule->alias->varAlias, ['as' => 'payonline', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@store']);
                        Route::get($arrModule->alias->varAlias . '/{alias}/preview', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                        Route::get($arrModule->alias->varAlias . '/{alias}/preview/detail', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);

                    break;
//                    case "complaint-services":
//
//                        
//                        Route::get($arrModule->alias->varAlias, ['as' => 'complaint-services', 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
//                        Route::get($arrModule->alias->varAlias . '/{alias}/preview', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
//                        Route::get($arrModule->alias->varAlias . '/{alias}/preview/detail', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);
//
//                    break;
                    case 'page_template':
                        Route::get($arrModule->alias->varAlias . '/{record}/preview', ['as' => $arrModule->alias->varAlias, 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                        Route::get($arrModule->alias->varAlias . '/{record}/preview/detail', ['as' => $arrModule->alias->varAlias, 'uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                        Route::post($arrModule->alias->varAlias . '/fetchdata', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@fetchData']);
                        break;
                    default:
                    		
                        if ($sector) {
                            Route::post($sector_slug . '/' . $arrModule->alias->varAlias . '/fetchdata', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@fetchData']);
                            Route::any($sector_slug . '/' . $arrModule->alias->varAlias, ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                            Route::any($sector_slug . '/' . $arrModule->alias->varAlias . '/{id}', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                            Route::get($arrModule->alias->varAlias . '/{alias}', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);
                            Route::get($arrModule->alias->varAlias . '/{alias}/preview', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                            Route::get($arrModule->alias->varAlias . '/{alias}/preview/detail', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);
                        } else {
                            Route::any($arrModule->alias->varAlias . '/', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                            Route::get($arrModule->alias->varAlias . '/{alias}', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);
                            Route::post($arrModule->alias->varAlias . '/fetchdata', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@fetchData']);
                            Route::get($arrModule->alias->varAlias . '/{alias}/preview', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@index']);
                            Route::get($arrModule->alias->varAlias . '/{alias}/preview/detail', ['uses' => $CONTROLLER_NAME_SPACE . $CONTROLLER_NAME . '@detail']);
                        }
                        break;
                }
            }
            if (isset($arrModule->modules->varModuleClass) && $arrModule->modules->varModuleClass == 'CmsPagesController') {
                $arrModule->modules->varModuleClass = 'PagesController';
            }
        }
        
        Route::get('submit-job-application', 'Powerpanel\Careers\Controllers\CareersController@index');
        Route::get('submit-job-application/thankyou', ['as' => 'submit-job-application/thankyou', 'uses' => 'ThankyouController@index']);
        Route::post('submit-job-application',['as' => 'submit-job-application', 'uses' => 'Powerpanel\Careers\Controllers\CareersController@submitJobApp']);
        Route::get('/{record}/preview', ['uses' => $CONTROLLER_NAME_SPACE . 'CmsPagesController@index']);
        Route::get('events-calender', $CONTROLLER_NAME_SPACE . 'EventsController@viewcalender');
        Route::post('insert-page-hits', $CONTROLLER_NAME_SPACE . 'FrontController@insertHits');
        Route::post('NotificationToken', $CONTROLLER_NAME_SPACE . 'FrontController@UpdateNotificationToken');

        Route::post('/Country_Data', $CONTROLLER_NAME_SPACE . 'FormBuilderController@Statecmb');
        Route::post('/PagePass_URL_Listing', $CONTROLLER_NAME_SPACE . 'FrontController@PagePassURLListing');
        Route::get('/viewPDF/{dir}/{foldername}/{filename}', ['uses' => $CONTROLLER_NAME_SPACE . 'PagesController@viewFolderPDF'])->name('viewFolderPDF');
        Route::get('/viewPDF/{dir}/{filename}', ['uses' => $CONTROLLER_NAME_SPACE . 'PagesController@viewPDF'])->name('viewPDF');

        Route::POST('/check_activity/no_secure', ['uses' => 'FrontController@check_activity_no_secure']);
        Route::get('/check_activity', ['uses' => 'FrontController@check_activity']);

        //Cron Routes=======================================
        Route::get('cron/workflow/{id}', ['uses' => 'CronController@workflow']);
        Route::post('/powerpanel/hits-report/mobilehist', ['uses' => 'HitsReportController@getPageHitChart']);
        Route::post('/powerpanel/hits-report/sendreport', ['uses' => 'HitsReportController@getSendChart']);
        //==================================================
        Route::get('/previewpage', ['uses' => '\Powerpanel\CmsPage\Controllers\CmsPagesController@previewpage'])->name('front.previewpage');
        Route::post('/fetchdata', ['uses' => '\Powerpanel\CmsPage\Controllers\CmsPagesController@fetchData']);
        Route::post('/emailToFriend', ['uses' => '\App\Http\Controllers\FrontController@emailToFriend']);
        Route::get('/search', ['uses' => '\App\Http\Controllers\SearchController@index'])->name('front.searchindex');
        Route::post('/search', ['uses' => '\App\Http\Controllers\SearchController@search'])->name('front.search');
        Route::post('/accept-privacy', $CONTROLLER_NAME_SPACE . 'FrontController@cookiesPopupStore');


         Route::post('/powerpanel/visualcomposer/get_dialog_maker', 'Powerpanel\VisualComposer\Controllers\VisualComposerController@get_dialog_maker');
        Route::post('/powerpanel/user_notification/update_read_all_status', 'Powerpanel\NotificationList\Controllers\Powerpanel\NotificationController@update_read_all_status');
        Route::post('/powerpanel/user_notification/update_read_status', 'Powerpanel\NotificationList\Controllers\Powerpanel\NotificationController@update_read_status');
        Route::post('/powerpanel/notification','Powerpanel\NotificationList\Controllers\Powerpanel\NotificationController@index');
        Route::post('/powerpanel/notification/update_read_status', 'Powerpanel\NotificationList\Controllers\Powerpanel\NotificationController@update_read_status');
        Route::post('/powerpanel/notification/get_read_notification_count','Powerpanel\NotificationList\Controllers\Powerpanel\NotificationController@get_read_notification_count');
        Route::post('news-letter', ['as' => 'news-letter', 'uses' => '\Powerpanel\NewsletterLead\Controllers\SubscriptionController@store']);
        Route::group(['namespace' => $CONTROLLER_NAME_SPACE], function () {

            Route::get('/', ['uses' => 'HomeController@index'])->name('home');
            Route::post('/print', ['uses' => 'PrintController@index'])->name('front.print');

            Route::post('/search/auto-complete', ['uses' => 'SearchController@autoComplete'])->name('front.searchauto');
            Route::post('/setDocumentHitcounter', ['as' => 'DocHitCounter', 'uses' => 'FrontController@setdocumentCounter']);
            Route::get('on-line-complaint-form/thankyou', ['as' => 'on-line-complaint-form/thankyou', 'uses' => 'ThankyouController@index']);
            Route::get('on-line-complaint-form', ['as' => 'on-line-complaint-form', 'uses' => '\Powerpanel\ComplaintServices\Controllers\ComplaintServicesController@index']);
            Route::post('on-line-complaint-form', ['as' => 'on-line-complaint-form', 'uses' => '\Powerpanel\ComplaintServices\Controllers\ComplaintServicesController@store']);
       
            Route::post('polling-lead', ['as' => 'polling-lead', 'uses' => 'PollingMasterController@store']);
            Route::get('news-letter/subscription/subscribe/{id}/{VarToken}', ['uses' => '\Powerpanel\NewsletterLead\Controllers\SubscriptionController@subscribe'])->name('subscribe');
            Route::get('news-letter/subscription/unsubscribe/{id}/{VarToken}', ['uses' => '\Powerpanel\NewsletterLead\Controllers\SubscriptionController@unsubscribe'])->name('unsubscribe');
            Route::post('feedback', ['as' => 'feedback', 'uses' => 'FeedbackController@store']);
            Route::post('cms', ['as' => 'cms', 'uses' => 'PagesController@store']);
            Route::post('emailtofriend', ['as' => 'emailtofriend', 'uses' => 'EmailtoFriendController@store']);
            Route::post('formbuildersubmit', ['as' => 'formbuildersubmit', 'uses' => '\Powerpanel\FormBuilder\Controllers\FormBuilderController@store']);
            Route::get('/formbuildersubmit/thankyou', ['as' => 'formbuildersubmit/thankyou', 'uses' => 'ThankyouController@index']);
            Route::get('/news-letter/thankyou', ['as' => 'news-letter/thankyou', 'uses' => 'ThankyouController@index']);
            Route::get('/thankyou', ['as' => 'thankyou', 'uses' => 'ThankyouController@index']);
            Route::get('/news-letter/failed', ['uses' => 'ThankyouController@subscribe_failed'])->name('news-letter/failed');
            Route::get('/news-letter/unsubscribed', ['uses' => 'ThankyouController@unsubscribed'])->name('news-letter/unsubscribed');
            Route::get('/news-letter/success', ['uses' => 'ThankyouController@success'])->name('news-letter/success');
            Route::get('sitemap', ['as' => 'sitemap', 'uses' => 'SiteMapController@index']);
            Route::get('download/{filename}', ['as' => 'download', 'uses' => 'FrontController@download']);
            Route::get('generateSitemap', ['as' => 'generateSitemap', 'uses' => 'SiteMapController@generateSitemap']);
            Route::get('sitemap.xml', ['uses' => 'SiteMapController@sitemapxml'])->name('sitemapxml');
            Route::post('/front/search', ['as' => 'search', 'uses' => 'FrontController@search']);
            Route::post('/front/popupvalue', ['as' => 'popupvalue', 'uses' => 'FrontController@popup']);
            Route::post('/email', ['uses' => 'EmailController@send_email']);
            Route::get('/email', ['uses' => 'EmailController@index']);
            Route::get('/fetchrss/{start}/{offset}', ['as' => 'fetchrss', 'uses' => 'FetchrssController@index']);
        });

        Route::group(['namespace' => $CONTROLLER_NAME_SPACE], function () {

            Route::post('powerpanel/sendResetLinkAjax', 'Auth\LoginController@login');
            Route::get('powerpanel/login', 'Auth\LoginController@showLoginForm')->name('login');
            Route::get('powerpanel/', 'Auth\LoginController@showLoginForm');
            Route::get('powerpanel/login', 'Auth\LoginController@showLoginForm')->name('login');
            Route::post('powerpanel/login', 'Auth\LoginController@login');
            Route::post('powerpanel/logout', 'Auth\LoginController@logout')->name('logout');
            Route::get('powerpanel/register', 'Auth\RegisterController@showRegistrationForm')->name('register');
            Route::post('powerpanel/register', 'Auth\RegisterController@register');
            Route::get('powerpanel/password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
            Route::post('powerpanel/password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
            Route::get('powerpanel/password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
            Route::post('powerpanel/password/reset', 'Auth\ResetPasswordController@reset')->name('password.reset.post');
            Route::post('/powerpanel/aliasGenerate', ['as' => 'powerpanel/aliasGenerate', 'uses' => 'PowerpanelController@aliasGenerate']);
            Route::get('powerpanel/install/{file}', 'PowerpanelController@install');

            Route::post('/powerpanel/Quickedit_Listing', array('uses' => 'PowerpanelController@Quickedit_Listing'));
            Route::post('/powerpanel/TrashData_Listing', array('uses' => 'PowerpanelController@TrashData_Listing'));
            Route::post('/powerpanel/RestoreData_Listing', array('uses' => 'PowerpanelController@RestoreData_Listing'));
            Route::post('/powerpanel/UnArchiveData_Listing', array('uses' => 'PowerpanelController@UnArchiveData_Listing'));
            Route::post('/powerpanel/Copy_Listing', array('uses' => 'PowerpanelController@Copy_Listing'));
            Route::post('/powerpanel/Favorite_Listing', array('uses' => 'PowerpanelController@Favorite_Listing'));
            Route::post('/powerpanel/Archive_Listing', array('uses' => 'PowerpanelController@Archive_Listing'));
            Route::post('/powerpanel/HideColumn', array('uses' => 'PowerpanelController@HideColumn'));
            Route::post('/powerpanel/RemoveDarftData', array('uses' => 'PowerpanelController@RemoveDarftData'));
            Route::post('/powerpanel/Notification_View', array('uses' => 'PowerpanelController@Notification_View'));
            Route::post('/powerpanel/Save_Data', array('uses' => 'PowerpanelController@Save_Data'));
            Route::post('/powerpanel/FormEditData', array('uses' => 'PowerpanelController@FormEditData'));
            Route::post('/powerpanel/header_notification_count', array('uses' => 'PowerpanelController@header_notification_count'));
            Route::get('/powerpanel/FolderImages', array('uses' => 'PowerpanelController@GetFolderImages'));
            Route::get('/powerpanel/GetFolderDocument', array('uses' => 'PowerpanelController@GetFolderDocument'));
            Route::get('/powerpanel/GetFolderAudio', array('uses' => 'PowerpanelController@GetFolderAudio'));
            Route::post('/powerpanel/Hits_Listing', array('uses' => 'PowerpanelController@Hits_Listing'));
            Route::post('/powerpanel/unlock_pagedata', array('uses' => 'PowerpanelController@unlock_pagedata'));
            Route::post('/powerpanel/lock_pagedata', array('uses' => 'PowerpanelController@lock_pagedata'));
            //Route::get('/powerpanel/assign-permissions', array('uses' => 'PowerpanelController@assignAllPermissonToSuperAdmin'));

            Route::post('/powerpanel/password/sendResetLinkAjax', 'Auth\ResetPasswordController@sendResetLinkAjax');

            //Alias Module Routes#####################
            Route::post('/powerpanel/aliasGenerate', ['as' => 'powerpanel/aliasGenerate', 'uses' => 'PowerpanelController@aliasGenerate']);
            Route::post('/powerpanel/generate-seo-content', ['as' => 'powerpanel/generate-seo-content', 'uses' => 'PowerpanelController@generateSeoContent']);
            //Alias Module Routes#####################

            Route::post('/powerpanel/ckeditor/upload-image', 'PowerpanelController@uploadImage');
            Route::post('/powerpanel/save-order', array('uses' => 'PowerpanelController@saveRsideBar'));
            Route::get('/powerpanel/get-sidebar-order', array('uses' => 'PowerpanelController@getRsideBar'));
        });

        Route::group(['namespace' => $CONTROLLER_NAME_SPACE . 'Powerpanel', 'middleware' => ['auth']], function ($request) {

            Route::get('powerpanel/verify', 'RandomController@randomverify');
            Route::get('powerpanel/question_verify', 'RandomController@question_verify');
            Route::post('powerpanel/checkrandom', 'RandomController@checkrandom');
            Route::post('powerpanel/checkanswer', 'UserController@checkanswer');
            Route::post('powerpanel/add-terms-read', 'TermsConditionsController@insertRead');
            Route::post('powerpanel/add-terms-acccept', 'TermsConditionsController@insertAccept');
            Route::post('powerpanel/terms-accepted-check', 'TermsConditionsController@checkAccepted');

            //Media Manager Module Routes#####################
            Route::post('/powerpanel/media/set_image_html', ['as' => 'powerpanel/media/set_image_html', 'uses' => 'MediaController@set_image_html']);
            Route::post('/powerpanel/media/ComposerDocData', ['as' => 'powerpanel/media/ComposerDocData', 'uses' => 'MediaController@ComposerDocData']);
            Route::post('/powerpanel/media/ComposerDocDatajs', ['as' => 'powerpanel/media/ComposerDocDatajs', 'uses' => 'MediaController@ComposerDocDatajs']);
            Route::post('/powerpanel/media/set_video_html', ['as' => 'powerpanel/media/set_video_html', 'uses' => 'MediaController@set_video_html']);
            Route::post('/powerpanel/media/upload_image', ['uses' => 'MediaController@upload_image'])->name('powerpanel/media/upload_image');
            Route::post('/powerpanel/media/upload_video', ['as' => 'powerpanel/media/upload_video', 'uses' => 'MediaController@upload_video']);
            Route::post('/powerpanel/media/user_uploaded_video', ['as' => 'powerpanel/media/user_uploaded_video', 'uses' => 'MediaController@user_uploaded_video']);
            Route::post('/powerpanel/media/get_trash_videos', ['as' => '/powerpanel/media/get_trash_videos', 'uses' => 'MediaController@get_trash_videos']);
            Route::post('/powerpanel/media/user_uploaded_image', ['as' => '/powerpanel/media/user_uploaded_image', 'uses' => 'MediaController@user_uploaded_image']);
            Route::post('/powerpanel/media/folder_uploaded_image', ['as' => '/powerpanel/media/folder_uploaded_image', 'uses' => 'MediaController@folder_uploaded_image']);
            Route::post('/powerpanel/media/load_more_images/{user_id}', ['as' => '/powerpanel/media/load_more_images', 'uses' => 'MediaController@load_more_images']);
            Route::post('/powerpanel/media/load_more_docs/{user_id}', ['as' => '/powerpanel/media/load_more_docs', 'uses' => 'MediaController@load_more_docs']);
            Route::post('/powerpanel/media/remove_image', ['as' => '/powerpanel/media/remove_image', 'uses' => 'MediaController@remove_image']);
            Route::post('/powerpanel/media/updateDocTitle', ['as' => '/powerpanel/media/updateDocTitle', 'uses' => 'MediaController@updateDocTitle']);
            Route::post('/powerpanel/media/updateAudioTitle', ['as' => '/powerpanel/media/updateAudioTitle', 'uses' => 'MediaController@updateAudioTitle']);
            Route::post('/powerpanel/media/get_recent_uploaded_images', ['as' => '/powerpanel/media/get_recent_uploaded_images', 'uses' => 'MediaController@get_recent_uploaded_images']);
            Route::post('/powerpanel/media/get_trash_images', ['as' => '/powerpanel/media/get_trash_images', 'uses' => 'MediaController@get_trash_images']);
            Route::post('/powerpanel/media/insert_image_by_url', ['as' => '/powerpanel/media/insert_image_by_url', 'uses' => 'MediaController@insert_image_by_url']);
            Route::post('/powerpanel/media/insert_video_by_url', ['as' => '/powerpanel/media/insert_video_by_url', 'uses' => 'MediaController@insert_video_by_url']);
            Route::post('/powerpanel/media/remove_multiple_image', ['as' => '/powerpanel/media/remove_multiple_image', 'uses' => 'MediaController@remove_multiple_image']);
            Route::post('/powerpanel/media/remove_multiple_videos', ['as' => '/powerpanel/media/remove_multiple_videos', 'uses' => 'MediaController@remove_multiple_videos']);
            Route::post('/powerpanel/media/restore_multiple_image', ['as' => '/powerpanel/media/restore_multiple_image', 'uses' => 'MediaController@restore_multiple_image']);
            Route::post('/powerpanel/media/restore-multiple-videos', ['as' => '/powerpanel/media/restore-multiple-videos', 'uses' => 'MediaController@restore_multiple_videos']);
            Route::post('/powerpanel/media/set_document_uploader', ['as' => 'powerpanel/media/set_document_uploader', 'uses' => 'MediaController@set_document_uploader']);
            Route::post('/powerpanel/media/set_audio_uploader', ['as' => 'powerpanel/media/set_audio_uploader', 'uses' => 'MediaController@set_audio_uploader']);
            Route::post('/powerpanel/media/upload_documents', ['as' => 'powerpanel/media/upload_documents', 'uses' => 'MediaController@upload_documents']);
            Route::post('/powerpanel/media/upload_audios', ['as' => 'powerpanel/media/upload_audios', 'uses' => 'MediaController@upload_audios']);
            Route::post('/powerpanel/media/user_uploaded_docs', ['as' => '/powerpanel/media/user_uploaded_docs', 'uses' => 'MediaController@user_uploaded_docs']);
            Route::post('/powerpanel/media/folder_uploaded_docs', ['as' => '/powerpanel/media/folder_uploaded_docs', 'uses' => 'MediaController@folder_uploaded_docs']);
            Route::post('/powerpanel/media/user_uploaded_audios', ['as' => '/powerpanel/media/user_uploaded_audios', 'uses' => 'MediaController@user_uploaded_audios']);
            Route::post('/powerpanel/media/folder_uploaded_audios', ['as' => '/powerpanel/media/folder_uploaded_audios', 'uses' => 'MediaController@folder_uploaded_audios']);
            Route::post('/powerpanel/media/remove_multiple_documents', ['as' => '/powerpanel/media/remove_multiple_documents', 'uses' => 'MediaController@remove_multiple_documents']);
            Route::post('/powerpanel/media/remove_multiple_audios', ['as' => '/powerpanel/media/remove_multiple_audios', 'uses' => 'MediaController@remove_multiple_audios']);
            Route::post('/powerpanel/media/get_trash_documents', ['as' => '/powerpanel/media/get_trash_documents', 'uses' => 'MediaController@get_trash_documents']);
            Route::post('/powerpanel/media/get_trash_audios', ['as' => '/powerpanel/media/get_trash_audios', 'uses' => 'MediaController@get_trash_audios']);
            Route::post('/powerpanel/media/get_trash_audios', ['as' => '/powerpanel/media/get_trash_audios', 'uses' => 'MediaController@get_trash_audios']);
            Route::post('/powerpanel/media/check-img-inuse', ['as' => '/powerpanel/media/check-img-inuse', 'uses' => 'MediaController@checkedUsedImg']);
            Route::post('/powerpanel/media/restore-multiple-document', ['as' => '/powerpanel/media/restore_multiple_document', 'uses' => 'MediaController@restore_multiple_document']);
            Route::post('/powerpanel/media/restore-multiple-audio', ['as' => '/powerpanel/media/restore_multiple_audio', 'uses' => 'MediaController@restore_multiple_audio']);
            Route::post('/powerpanel/media/check-document-inuse', ['as' => '/powerpanel/media/check-document-inuse', 'uses' => 'MediaController@checkedUsedDocument']);
            Route::post('/powerpanel/media/check-audio-inuse', ['as' => '/powerpanel/media/check-audio-inuse', 'uses' => 'MediaController@checkedUsedAudio']);
            Route::post('/powerpanel/media/get_image_details', ['uses' => 'MediaController@getImageDetails'])->name('get_image_details');
            Route::post('/powerpanel/media/save_image_details', ['uses' => 'MediaController@saveImageDetails'])->name('save_image_details');
            Route::post('/powerpanel/media/crop_image', ['uses' => 'MediaController@cropImage'])->name('crop_image');
            Route::post('/powerpanel/media/save_cropped_image', ['uses' => 'MediaController@saveCroppedImage'])->name('save_cropped_image');
            Route::post('/powerpanel/media/check-video-inuse', ['as' => '/powerpanel/media/check-video-inuse', 'uses' => 'MediaController@checkedUsedVideo']);
            Route::post('/powerpanel/media/empty_trash_Image', ['as' => 'powerpanel/media/empty_trash_image', 'uses' => 'MediaController@empty_trash_image']);
            Route::post('/powerpanel/media/empty_trash_Video', ['as' => 'powerpanel/media/empty_trash_video', 'uses' => 'MediaController@empty_trash_video']);
            Route::post('/powerpanel/media/empty_trash_Document', ['as' => 'powerpanel/media/empty_trash_document', 'uses' => 'MediaController@empty_trash_document']);
            Route::post('/powerpanel/media/empty_trash_Audio', ['as' => 'powerpanel/media/empty_trash_audio', 'uses' => 'MediaController@empty_trash_audio']);
            Route::post('/powerpanel/media/get_video_byUrl_html', ['as' => '/powerpanel/media/get_video_byUrl_html', 'uses' => 'MediaController@get_video_byUrl_html']);
            //Media Manager Routes#####################

        });

        if ($setConstants) {
            Route::group(['namespace' => $CONTROLLER_NAME_SPACE . 'Powerpanel', 'middleware' => ['auth']], function ($request) {
                Route::post('/powerpanel/appointment-lead/saveComment', 'AppointmentLeadController@saveComment');
            });
        }

        Route::post('/powerpanel/share', ['uses' => $CONTROLLER_NAME_SPACE . 'OnePushController@ShareonSocialMedia']);
        Route::post('/powerpanel/share/getrec', ['uses' => $CONTROLLER_NAME_SPACE . 'OnePushController@getRecord']);
        Route::get('/powerpanel/share/gPlusCallBack', ['uses' => $CONTROLLER_NAME_SPACE . 'OnePushController@gPlusCallBack']);

        Route::group(['namespace' => $CONTROLLER_NAME_SPACE . 'Powerpanel', 'middleware' => ['auth']], function ($request) {
            Route::get('/powerpanel/security-settings', array('uses' => 'SecuritySettingsController@index'));
        });

    }

}
