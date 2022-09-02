<?php

namespace App\Exceptions;

use App\EmailType;
use App\Http\Traits\slug;
use Config;
use File;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Menu\Models\Menu;
use Powerpanel\ShieldCMSTheme\Models\ErrorLog;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Session\TokenMismatchException;
use Throwable;
use Auth;
use Session;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldReport($exception)) {
            //$this->sendEmail($exception); // sends an email
        }

        parent::report($exception);
    }

    public function sendEmail(Throwable $exception)
    {
        try {

            $e = FlattenException::create($exception);
            $handler = new SymfonyExceptionHandler();
            $html = $handler->getHtml($e);

            $settings = \App\Helpers\Email_sender::getSettings();

            $subject = str_limit($exception->getMessage(), 400);

            $settings["subject"] = "Ofreg: " . $subject;
            $settings['emailType'] = EmailType::getRecords()->checkEmailType('Error Logs')->first()->id;
            $settings['from'] = \App\Helpers\Mylibrary::getLaravelDecryptedString($settings['DEFAULT_EMAIL']);
            $settings['to'] = \App\Helpers\Mylibrary::getLaravelDecryptedString($settings['email']);
            $settings['sender'] = $settings['SMTP_SENDER_NAME'];
            $settings['receiver'] = $settings['SMTP_SENDER_NAME'];
            $settings['txtBody'] = view('emails.exception', ['content' => $html])->render();

            $laravelLogs = array();
            $laravelLogs['varTitle'] = $exception->getMessage();
            $laravelLogs['txtErrorTemplate'] = $settings['txtBody'];
            $laravelLogs['varIpAddress'] = \App\Helpers\Mylibrary::get_client_ip();
            $laravelLogId = ErrorLog::insertGetId($laravelLogs);

            //$logId = \App\Helpers\Email_sender::recodLog($settings);
            //\App\Helpers\Email_sender::sendEmail('emails.exception', $settings, $logId);
        } catch (Throwable $ex) {
            abort(500);
        }
    }


    public static function loadHeaderMenu($menuItems) {
        
        $html = '';
        if(isset($menuItems[1]) && count($menuItems[1]) > 0) {

            $menuArr = Self::buildTree($menuItems[1]);
            $html .= '<ul class="brand-nav brand-navbar" id="headerMenu">';
            foreach ($menuArr as $key => $row) {

                $activeclass = '';
                $currenturl = Request::segment(1) . '/' . Request::segment(2);
                if (Request::segment(1) != '') {
                    if (Request::segment(1) == $row['txtPageUrl']) {
                        $activeclass = "active";
                    } else if ($currenturl == $row['txtPageUrl']) {
                        $activeclass = "active";
                    } else {
                        $activeclass = '';
                    }
                }

                if ($row['txtPageUrl'] == 'javascript:;') {
                    $menuurl = 'javascript:;';
                } else {
                    $menuurl = url($row['txtPageUrl']);
                }

                $html .= '<li class="first ' . $activeclass . '"><a href="' . $menuurl . '" title="' . ucfirst($row['varTitle']) . '">' . ucfirst($row['varTitle']) . '</a>';
                $html .= Self::getHeaderChildMenuItems($row);
                $html .= '</li>';
            }
            $html .= '</ul>';
            
        }
        view()->share('HeadreMenuhtml', $html);
        return $html;
    }

    public static function getHeaderChildMenuItems($menuObj) {

        $html = '';
        if (isset($menuObj['children']) && !empty($menuObj['children'])) {
            $html .= '<span class="is-open"></span>';
            $html .= '<ul class="sub-menu">';
            foreach ($menuObj['children'] as $key => $nav) {

                $activeclass = '';
                $currenturl = Request::segment(1) . '/' . Request::segment(2);
                if (Request::segment(1) != '') {
                    if (Request::segment(1) == $nav['txtPageUrl']) {
                        $activeclass = "active";
                    } else if ($currenturl == $nav['txtPageUrl']) {
                        $activeclass = "active";
                    } else {
                        $activeclass = '';
                    }
                }

                if ($nav['txtPageUrl'] == 'javascript:;') {
                    $menuurl = 'javascript:;';
                } else {
                    $menuurl = url($nav['txtPageUrl']);
                }

                $html .= '<li class="first ' . $activeclass . '">';
                $html .= '<a href="' . $menuurl . '" title="' . ucfirst($nav['varTitle']) . '">' . ucfirst($nav['varTitle']) . '</a>';
                if (isset($nav['children']) && !empty($nav['children'])) {
                    $html .= Self::getHeaderChildMenuItems($nav);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public static function getNavigationMenu($menuItems) {

        $html = '';
        if(isset($menuItems[3]) && count($menuItems[3]) > 0) {

            $menuArr = Self::buildTree($menuItems[3]);
            $html .= '<ul id="accordionMenu" class="brand-nav brand-navbar">';
            foreach ($menuArr as $navmenu) {
    
                if ($navmenu['intParentMenuId'] == 0) {

                    if (isset($navmenu['children']) && !empty($navmenu['children'])) {
                        $html .= '<li class="sub-menu1">';
                    } else {
                        $html .= '<li>';
                    }
                    $menuURL = url($navmenu['txtPageUrl']);
                    $currentURL = URL::current();
    
                    $class = '';
                    if ($menuURL == $currentURL) {
                        $class = 'active';
                    }
                    $html .= '<a href="' . url($navmenu['txtPageUrl']) . '" title="' . ucfirst($navmenu['varTitle']) . '" data-content="' . ucfirst($navmenu['varTitle']) . '">' . ucfirst($navmenu['varTitle']) . '</a> <span class="collapsed" data-toggle="collapse" data-target="#' . ucfirst($navmenu['varTitle']) . '" aria-expanded="false" aria-controls="' . ucfirst($navmenu['varTitle']) . '"></span>';
                    $html .= Self::getNavigationChildMenu($navmenu);
                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public static function getNavigationChildMenu($navmenu) {

        $html = '';
        if (isset($navmenu['children']) && !empty($navmenu['children'])) {
            $html .= '<ul id="' . ucfirst($navmenu['varTitle']) . '" class="sub-menu collapse" data-parent="#accordionMenu">';
            foreach ($navmenu['children'] as $nav) {
                $html .= '<li>';
                $menuURL = url($nav['txtPageUrl']);
                $html .= '<a href="' . url($nav['txtPageUrl']) . '" title="' . ucfirst($nav['varTitle']) . '">' . ucfirst($nav['varTitle']) . '</a>';
                if (isset($navmenu['children']) && !empty($navmenu['children'])) {
                    $html .= Self::getNavigationChildMenu($nav);
                }
                $html .= '</li>';
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public static function getFooterMenuByTypeID($menuItems) {

        $html = '';
        if(count($menuItems) > 0) {
            $html .= '<ul class="nqul default-nav">';
            foreach ($menuItems as $navmenu) {

                if ($navmenu['intParentMenuId'] == 0) {
                    $html .= '<li>';

                    $menuURL = url($navmenu['txtPageUrl']);
                    $currentURL = URL::current();

                    $class = '';
                    if ($menuURL == $currentURL) {
                        $class = 'active';
                    }

                    $html .= '<a class=" ' . $class . '"  href="' . $menuURL . '" title="' . ucfirst($navmenu['varTitle']) . '" data-content="' . ucfirst($navmenu['varTitle']) . '">' . ucfirst($navmenu['varTitle']) . '</a>';

                    $html .= '</li>';
                }
            }
            $html .= '</ul>';
        }
        return $html;
    }

    public static function loadQuickLinksMenu($menuItems)
    {
        $html = '';
        if (empty($menuItems[3][0]['menu_type'])) {
            $html = '';
        } else {
            if (isset($menuItems[3]) && count($menuItems[3]) > 0) {

                $menuArr = Self::buildTree($menuItems[3]);
                $html .= '<ul class="quick-link" id="quickLinksMenu">';
                foreach ($menuArr as $key => $row) {
                    $activeclass = '';
                    $currenturl = Request::segment(1) . '/' . Request::segment(2);
                    $fullUrl = URL::current();
                    if($fullUrl == url($row['txtPageUrl'])) {
                        $activeclass = "active";
                    }else if (Request::segment(1) == $row['txtPageUrl']) {
                        $activeclass = "active";
                    } else if ($currenturl == $row['txtPageUrl']) {
                        $activeclass = "active";
                    } else {
                        $activeclass = '';
                    }

                    if ($row['txtPageUrl'] == 'javascript:;') {
                        $menuurl = 'javascript:;';
                    } else {
                        $menuurl = url($row['txtPageUrl']);
                    }

                    $html .= '<li class="' . $activeclass . '"><a href="' . $menuurl . '" title="' . ucfirst($row['varTitle']) . '">' . ucfirst($row['varTitle']) . '</a>';
                    $html .= Self::getHeaderChildMenuItems($row);
                    $html .= '</li>';
                }
                $html .= '</ul>';
            }
        }
        view()->share('QuickLinksMenu', $html);
        return $html;
    }

    public static function buildTree(array $elements, $parentId = 0) {
        
        $branch = array();
        foreach ($elements as $element) {
            if ($element['intParentMenuId'] == $parentId) {
                $children = Self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[] = $element;
            }
        }
        return $branch;
    }


    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Throwable $exception)
    {	
    		$errorTmp = [
    			'server'=>$request->server(),
    			'attributes'=>$request->attributes,
    			'request'=>$request->request,
    			'query'=>$request->query()
    		];

        if (!empty($exception->getMessage())) {

            ErrorLog::create([
                'varTitle' => $exception->getMessage(),
                'txtErrorTemplate' => json_encode($errorTmp),
                'varIpAddress' => \App\Helpers\Mylibrary::get_client_ip(),
            ]);
            //$to = ['aapatel@netclues.com','tdave'];
            //Email_sender::sendErrorReport($to,$exception->getMessage());
        }

        if ($exception instanceof NotFoundHttpException) {
            
            if (Request::segment(1) != "powerpanel") {
                $cmsPageId = slug::resolve_alias_for_routes(!empty(Request::segment(1)) ? Request::segment(1) : 'home');
                $pageCms = null;

                if (is_numeric($cmsPageId)) {
                    $pageCms = CmsPage::getPageByPageId($cmsPageId);
                }

                $shareData['META_TITLE'] = 'Oops! 404 The requested page not found';
                $shareData['META_KEYWORD'] = 'Oops! 404 The requested page not found';
                $shareData['META_DESCRIPTION'] = 'Oops! 404 The requested page not found';

                $allMenu = Menu::getAllMenuItems();
                $shareData['HeadreMenuhtml'] = Self::loadHeaderMenu($allMenu);
                $shareData['navigationMenu'] = Self::getNavigationMenu($allMenu);
                $shareData['QuickLinksMenu'] = Self::loadQuickLinksMenu($allMenu);
                $shareData['footerMenu'] = ''; 
                if(isset($allMenu[2])) {
                    $shareData['footerMenu'] = Self::getFooterMenuByTypeID($allMenu[2]);
                }

                $shareData['APP_URL'] = Config::get('Constant.ENV_APP_URL');
                $shareData['SHARE_IMG'] = Config::get('Constant.FRONT_LOGO_ID');
                $shareData['currentPageTitle'] = '404 Page Not Found';
                $shareData['CDN_PATH'] = Config::get('Constant.CDN_PATH');

                if (File::exists(base_path() . '/packages/Powerpanel/ContactInfo/src/Models/ContactInfo.php')) {
                    $contacts = \Powerpanel\ContactInfo\Models\ContactInfo::getContactDetails();

                    foreach ($contacts as $contact) {
                        if (isset($contact->chrIsPrimary) && $contact->chrIsPrimary == 'Y') {
                            $objContactInfo = $contact;
                        }
                        if (isset($contact->chrIsPrimary) && $contact->chrIsPrimary == 'N') {
                            $secondaryaddress = $contact;
                        }
                    }
                    $shareData['objContactInfo'] = (!empty($objContactInfo)) ? $objContactInfo : '';
                }
                return response()->view('errors.404', $shareData, 404);
            } else {
                return response()->view('powerpanel.errors.404', [], 404);
            }
        }

        if(Request::segment(1) == "powerpanel") {

            if ($exception instanceof TokenMismatchException) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'code' => 401,
                        'error' => 'Token mismatch'
                    ], 401);
                };
            }
        }
        
        return parent::render($request, $exception);
    }

}
