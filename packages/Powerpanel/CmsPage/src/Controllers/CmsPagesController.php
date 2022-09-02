<?php

namespace Powerpanel\CmsPage\Controllers;

use App\CommonModel;
use App\Helpers\Aws_File_helper;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use DB;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\RoleManager\Models\Role;
use Request;
use Response;

class CmsPagesController extends FrontController
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
     * This method loads CMS Termsconditions list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */
    public function index()
    {
        $data = array();
        
        $sector = false;
        $sector_slug = '';
        $segment1 = Request::segment(1);
        $segment2 = Request::segment(2);

        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy" || $segment1 == "spectrum") && (!empty($segment2))) {
            $sector = true;
            $sector_slug = Request::segment(1);
        }

        if ($sector) {
            $pagename = $segment2;
        } else {
            $pagename = $segment1;
        }

        $aliasId = slug::resolve_alias($pagename, $sector_slug, 3);
        if(Request::segment(3) == 'preview') {
            $cmsPageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($cmsPageId, false);
        }else{
            if(Request::segment(3) !== null) {
                $aliasId = Mylibrary::getDecryptedString(Request::segment(3));
            }
            $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
        }

        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
            $role = Role::getRecordById(Role_user::getRecordBYModelId($user_id)->role_id)->name;
        } else {
            $user_id = '';
            $role = '';
        }

        if (isset($pageContent) && $pageContent->chrPageActive == 'PR') {
            if ($pageContent->UserID == $user_id || $role == 'netquick_admin') {
                if (Request::segment(3) !== null) {
                    $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                } else {
                    abort(404);
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($pageContent) && $pageContent->chrPageActive == 'PP') {
            $data['PassPropage'] = 'PP';
            $data['tablename'] = 'cms_page';
            $data['Pageid'] = $pageContent->id;
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true : false;
        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
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

        $data['breadcrumb'] = $this->breadcrumb;
        return view('cmspage::frontview.pages', $data);

    }

    public function checkCmsPageDesign($pageName)
    {
        return view('cmspage::frontview.html-pages', compact('pageName'));
    }

    public function viewPDF($dir, $filename)
    {
        $AWSContants = MyLibrary::getAWSconstants();
        if ($AWSContants['BUCKET_ENABLED'] == true) {
            $file_path = $dir . "/" . $filename;
            echo $data['modulePageUrl'] = Aws_File_helper::getObject_new($file_path);
            exit;
        } else {
            $pathToFile = public_path($dir . "/" . $filename);
            $headers = [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="' . $filename . '"',
            ];
            return response()->file($pathToFile, $headers);
        }
    }

    public function store()
    {
        $updateMenuFields = [
            'txtDescription' => $_REQUEST['txtDescription'],
        ];
        $whereConditions = ['id' => $_REQUEST['cms_id']];
        $update = CommonModel::updateRecords($whereConditions, $updateMenuFields, false, '\\App\\CmsPage');
        return json_encode(['success' => 'Cms Content Updated.']);
    }

    public function PagePassURLListing()
    {
        $record = Request::input();
        $pagedata = DB::table('cms_page')
            ->select('*')
            ->where('id', '=', $record['id'])
            ->first();
        if ($pagedata->varPassword == $record['passwordprotect']) {
            $html = FrontPageContent_Shield::renderBuilder($pagedata->txtDescription);
            echo json_encode($html);
        } else {
            $response = array("error" => 1, 'validatorErrors' => 'Password Does Not Match');
            echo json_encode($response);
        }
    }

    public function previewpage()
    {
        return view('layouts.preview');
    }

    public function fetchData(Request $request)
    {
        $requestArr = Request::all();

        if (isset($requestArr['pageName']) && !empty($requestArr['pageName'])) {
            if (is_numeric($requestArr['pageName']) && (int) $requestArr['pageName'] > 0) {
                $aliasId = $requestArr['pageName'];
            } else {
                $aliasId = slug::resolve_alias($requestArr['pageName']);
            }

            if (is_numeric($aliasId)) {
                $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
                if (!isset($pageContent->id)) {
                    $pageContent = CmsPage::getPageByPageId($aliasId, false);
                }
            }

            $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
            $data['PageData'] = '';
            if (isset($pageContent) && $pageContent->chrPageActive == 'PR') {
                if ($pageContent->UserID == $user_id) {
                    if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                        $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                    }
                } else {
                    return redirect(url('/'));
                }
            } else if (isset($pageContent) && $pageContent->chrPageActive == 'PP') {
                $data['PassPropage'] = 'PP';
                $data['Pageid'] = $pageContent->id;
            } else {
                if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                    $txtDesc = json_decode($pageContent->txtDescription);

                    foreach ($txtDesc as $key => $value) {
                        if ($value->type == 'row_template') {
                            if (isset($requestArr['month']) && !empty($requestArr['month'])) {
                                $value->filter['month'] = $requestArr['month'];
                            }
                            if (isset($requestArr['year']) && !empty($requestArr['year'])) {
                                $value->filter['year'] = $requestArr['year'];
                            }
                            if (isset($requestArr['category']) && !empty($requestArr['category'])) {
                                $value->val->filter['category'] = $requestArr['category'];
                            }
                        }
                    }
                    $pageContent->txtDescription = json_encode($txtDesc);

                    $response = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                    return $response;
                }

            }

        }
    }

}
