<?php
namespace Powerpanel\Alerts\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\Alerts\Models\Alerts;
use Powerpanel\CmsPage\Models\CmsPage;
use Request;

class AlertsController extends FrontController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * This method loads Alerts list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */
    public function index()
    {
        $data = array();
        $pagename = Request::segment(1);

        $aliasId = slug::resolve_alias($pagename);
        if (Request::segment(3) == 'preview') {
            $cmsPageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($cmsPageId, false);
        } else {
            $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
        }

        // Start CMS PAGE Front Private, Password Prottected Code

        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
        } else {
            $user_id = '';
        }
        if (isset($pageContent) && $pageContent->chrPageActive == 'PR') {
            if ($pageContent->UserID == $user_id) {
                if (isset($pageContent) && $pageContent != '') {
                    $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($pageContent) && $pageContent->chrPageActive == 'PP') {
            $data['PassPropage'] = 'PP';
            $data['Pageid'] = $pageContent->id;
        } else {
            if (isset($pageContent) && $pageContent != '') {
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            } else {
                $data['PageData'] = '';
            }
            $data['pageContent'] = $pageContent;
        }

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        return view('alerts::frontview.alerts', $data);
    }

}
