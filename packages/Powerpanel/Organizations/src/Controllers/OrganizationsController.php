<?php
namespace Powerpanel\Organizations\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use config;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Organizations\Models\Organizations;
use Powerpanel\RoleManager\Models\Role;
use Request;

class OrganizationsController extends FrontController
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        Config::set('Constant.MODULE.MODEL_ALIAS', 'Powerpanel\Organizations\Models\\');
    }

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

        if (!isset($pageContent->id)) {
            abort('404');
        }

        $CONTENT = ' <h2 class="no_record coming_soon_rcd"> Coming Soon</h2>';
        if (!empty($pageContent->txtDescription)) {
            $CONTENT = $pageContent->txtDescription;
        }

        // Start CMS PAGE Front Private, Password Prottected Code

        $pageContentcms = CmsPage::getPageContentByPageAlias($aliasId);
        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
            $role = Role::getRecordById(Role_user::getRecordBYModelId($user_id)->role_id)->name;
        } else {
            $user_id = '';
            $role = '';
        }

        if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PR') {
            if ($pageContentcms->UserID == $user_id || $role == 'netquick_admin') {
                if (isset($pageContent) && $pageContent != '') {
                    $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($pageContentcms) && $pageContentcms->chrPageActive == 'PP') {
            $data['PassPropage'] = 'PP';
            $data['tablename'] = 'cms_page';
            $data['Pageid'] = $pageContent->id;
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true : false;
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
        return view('organizations::frontview.organizations', $data);
    }

}
