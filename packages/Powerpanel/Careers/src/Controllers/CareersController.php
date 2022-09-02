<?php

namespace Powerpanel\Careers\Controllers;

use App\Helpers\Aws_File_helper;
use App\Helpers\Email_sender;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Config;
use File;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\Careers\Models\CareerLead;
use Powerpanel\Careers\Models\Careers;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\RoleManager\Models\Role;
use Request;
use Validator;
use App\Rules\ValidRecaptcha;

class CareersController extends FrontController
{

    use slug;

    protected $BUCKET_ENABLED;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->BUCKET_ENABLED = Config::get('Constant.BUCKET_ENABLED');
    }

    /**
     * This method loads Careers list view
     * @return  View
     * @since   2018-08-27
     * @author  NetQuick
     */
    public function index()
    {

        $data = array();

        $segment1 = Request::segment(1);
        $segment2 = Request::segment(2);

        $sector = false;
        $sector_slug = '';
        if (($segment1 == "ict" || $segment1 == "water" || $segment1 == "fuel" || $segment1 == "energy") && (!empty($segment2))) {
            $sector = true;
            $sector_slug = Request::segment(1);
        }

        if ($sector) {
            $pagename = $segment2;
        } else {
            $pagename = $segment1;
        }

        $aliasId = slug::resolve_alias_for_routes($pagename, $sector_slug);
        if (Request::segment(3) == 'preview') {
            $pageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($pageId, false);
        } else {
            $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
        }

        // Start CMS PAGE Front Private, Password Prottected Code
        $user_id = '';
        $role = '';
        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
            $role = Role::getRecordById(Role_user::getRecordBYModelId($user_id)->role_id)->name;
        }

        $data['PageData'] = '';
        if (isset($pageContent) && $pageContent->chrPageActive == 'PR') {
            if ($pageContent->UserID == $user_id || $role == 'netquick_admin') {
                if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                    $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
                }
            } else {
                return redirect(url('/'));
            }
        } else if (isset($pageContent) && $pageContent->chrPageActive == 'PP') {
            $data['PassPropage'] = 'PP';
            $data['tablename'] = 'cms_page';
            $data['Pageid'] = $pageContent->id;
            $data['pageContent'] = $pageContent;
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true : false;

        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            // $data['pageContent'] = $pageContent;
        }

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        return view('careers::frontview.careers', $data);
    }

    public function detail($alias)
    {
        $breadcrumb = [];
        $data = [];

        if (is_numeric($alias)) {
            $careers = Careers::getRecordById($alias);
        } else {
            $id = slug::resolve_alias($alias);
            $careers = Careers::getFrontDetail($id);
        }

        if (!empty($careers)) {
            $metaInfo = array('varMetaTitle' => $careers->varMetaTitle, 'varMetaKeyword' => $careers->varMetaKeyword, 'varMetaDescription' => $careers->varMetaDescription);
            if (isset($careers->varMetaTitle) && !empty($careers->varMetaTitle)) {
                view()->share('META_TITLE', $careers->varMetaTitle);
            }
            if (isset($careers->varMetaKeyword) && !empty($careers->varMetaKeyword)) {
                view()->share('META_KEYWORD', $careers->varMetaKeyword);
            }
            if (isset($careers->varMetaDescription) && !empty($careers->varMetaDescription)) {
                view()->share('META_DESCRIPTION', substr(trim($careers->varMetaDescription), 0, 500));
            }
            view()->share('ogImage', '');

            $moduelFrontPageUrl = MyLibrary::getFront_Uri('careers')['uri'];
            $moduleFrontWithCatUrl = ($alias != false) ? $moduelFrontPageUrl : $moduelFrontPageUrl;
            $breadcrumb['title'] = (!empty($careers->varTitle)) ? ucwords($careers->varTitle) : '';
            $breadcrumb['url'] = MyLibrary::getFront_Uri('careers')['uri'];

            $data['moduleTitle'] = 'Careers';
            $data['detailPageTitle'] = 'Careers';
            $data['career'] = $careers;
            $data['alias'] = $alias;
            $data['metaInfo'] = $metaInfo;
            $data['breadcrumb'] = $breadcrumb;
            $data['txtDescription'] = FrontPageContent_Shield::renderBuilder($careers->txtDescription);

            return view('careers::frontview.careers-detail', $data);
        } else {
            abort(404);
        }
    }

    public function fetchData(Request $request)
    {
        $requestArr = Request::all();
        if (isset($requestArr['pageName']) && !empty($requestArr['pageName'])) {
            if (is_numeric($requestArr['pageName']) && (int) $requestArr['pageName'] > 0) {
                $aliasId = $requestArr['pageName'];
            } else {
                $aliasId = slug::resolve_alias_for_routes($requestArr['pageName']);
            }

            if (is_numeric($aliasId)) {
                $pageContent = CmsPage::getPageContentByPageAlias($aliasId);
                if (!isset($pageContent->id)) {
                    $pageContent = CmsPage::getPageByPageId($aliasId, false);
                }
            }
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $txtDesc = json_decode($pageContent->txtDescription);
                foreach ($txtDesc as $key => $value) {
                    if ($value->type == 'career_template') {
                        if (isset($requestArr['pageNumber']) && !empty($requestArr['pageNumber'])) {
                            $value->val->filter['pageNumber'] = $requestArr['pageNumber'];
                        }
                    }
                }
                $pageContent->txtDescription = json_encode($txtDesc);
                $response = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);

                return $response;
            }
        }
    }

    public function submitJobApp(HttpRequest $request)
    {
        $data = $request->all();
        $ID = $request->id;

        $messsages = array(
            'fname.required' => 'Name field is required',
            'fname.handle_xss' => 'Please enter valid input',
            'fname.no_url' => 'URL is not allowed',
            'lname.required' => 'Last Name field is required',
            'email.required' => 'Email field is required',
            'phoneNo.required' => 'Phone No. field is required',
            'address1.required' => 'address1 field is required',
            'country.required' => 'Country field is required',
            'state.required' => 'State field is required',
            'city.required' => 'city field is required',
            'postalCode.required' => 'Postal Code field is required',
            'dob.required' => 'Date Of Birth field is required',
            'resume.required' => 'Resume field is required',
            'g-recaptcha-response.required' => 'Captcha is required',
            'resume.mimes' => 'Upload valid docs type'
        );

        $rules = array(
            'fname' => ['required', 'handle_xss', 'no_url'],
            'lname' => ['required', 'handle_xss', 'no_url'],
            'email' => ['required', 'handle_xss', 'no_url'],
            'phoneNo' => ['required', 'handle_xss', 'no_url'],
            'address1' => ['required', 'handle_xss'],
            'address2' => ['handle_xss'],
            'country' => ['required', 'handle_xss', 'no_url'],
            'state' => ['required', 'handle_xss', 'no_url'],
            'city' => ['required', 'handle_xss', 'no_url'],
            'postalCode' => ['required', 'handle_xss'],
            'dob' => ['required', 'handle_xss'],
            'resume' => ['required', 'mimes:docx,doc,pdf'],
            'g-recaptcha-response' => ['required']
        );
        // $rules['g-recaptcha-response'] = ['required', new ValidRecaptcha];

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {

            $career_lead = new CareerLead;
            $career_lead->careerId = (isset($data['careerId']) && !empty($data['careerId'])) ? $data['careerId'] : '';
            $career_lead->varTitle = strip_tags($data['fname']);
            $career_lead->varLastName = strip_tags($data['lname']);
            $career_lead->varEmail = MyLibrary::getEncryptedString($data['email']);
            $career_lead->varPhoneNo = MyLibrary::getEncryptedString($data['phoneNo']);
            $career_lead->varAddress1 = strip_tags($data['address1']);
            $career_lead->varAddress2 = strip_tags($data['address2']);
            $career_lead->varCountry = strip_tags($data['country']);
            $career_lead->varState = strip_tags($data['state']);
            $career_lead->varCity = strip_tags($data['city']);
            $career_lead->varPostalCode = strip_tags($data['postalCode']);
            $career_lead->dob = (date('Y-m-d', strtotime($data['dob'])));

            if ($data['gender'] == 'M') {
                $career_lead['gender'] = 'M';
            } else {
                $career_lead['gender'] = $data['gender'];
            }

            $career_lead->varImmigrationStatus = strip_tags($data['immigrationStatus']);
            $career_lead->varJobOpening = strip_tags($data['jobOpening']);
            $career_lead->varDescribeExp = strip_tags($data['describeExp']);
            $career_lead->varReasonForChange = strip_tags($data['reasonForChange']);
            $career_lead->varWhenToStart = strip_tags($data['whenToStart']);

            $saved_name = "";
            $saved_file_name = "";
            $original_name1 = "";
            if (isset($data['resume'])) {
                // $in_file = '';
                $in_file = Request::file('resume') ? Request::file('resume') : array();
                if (!empty($in_file)) {

                    $error = $in_file->getError();
                    $original_name = $in_file->getClientOriginalName();
                    $sourceFilePath = $in_file->getPathName();
                    $fileInfo = pathinfo($original_name);
                    $file_name = Self::clean($fileInfo['filename']) . '.' . strtolower($fileInfo['extension']);
                    if ($error == UPLOAD_ERR_OK) {
                        $destinationPath = 'cdn\career_documents';
                        if ($this->BUCKET_ENABLED) {
                            Aws_File_helper::putObject($sourceFilePath, 'career_documents/', $file_name);
                        } else {
                            $in_file->move($destinationPath, $file_name);
                            $data['attachement'] = url('/') . "cdn\career_documents" . $file_name;
                        }
                    }
                    $career_lead->resume = $file_name;
                }
            }

            $career_lead->varIpAddress = MyLibrary::get_client_ip();
            $career_lead->save();

            if ($this->BUCKET_ENABLED) {
                $data['savedFiles'] = Config::get('Constant.CDN_PATH') . "career_documents/" . $file_name;
            } else {
                $data['savedFiles'] = url('/') . "cdn\career_documents" . $file_name;
            }

            $data['original_name'] = $in_file->getClientOriginalName();

            /*Start this code for message*/
            if (!empty($career_lead->id)) {
                $recordID = $career_lead->id;
                Email_sender::careerMail($data, $recordID);

                if (Request::ajax()) {
                    return json_encode(['success' => 'Thank you for contacting us, We will get back to you shortly.']);
                } else {
                    return redirect()->route('thankyou')->with(['form_submit' => true, 'message' => 'Thank you for your Application, We will get back to you shortly.']);
                }

            } else {
                // return redirect('/');
                return redirect()->route('thankyou')->with(['form_submit' => true, 'message' => 'Thank you for your Application, We will get back to you shortly.']);

            }

        } else {

            //return contact form with errors
            if (!empty($data['back_url'])) {
                return redirect($data['back_url'] . '#job_application_form')->withErrors($validator)->withInput();
            } else {
                return Redirect()->back()->withErrors($validator)->withInput();
                // return Redirect::route('careers')->withErrors($validator)->withInput();
            }

        }
    }

    public static function clean($string)
    {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
    }

}
