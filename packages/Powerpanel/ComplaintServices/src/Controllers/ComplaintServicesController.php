<?php

namespace Powerpanel\ComplaintServices\Controllers;

use App\Helpers\Aws_File_helper;
use App\Helpers\Email_sender;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Config;
use File;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Companies\Models\Companies;
use Powerpanel\ComplaintLead\Models\ComplaintLead;
use Powerpanel\ComplaintServices\Models\ComplaintServices;
use Powerpanel\RoleManager\Models\Role;
use Request;
use Validator;

class ComplaintServicesController extends FrontController
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
     * This method loads ComplaintServices list view
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

        if (isset(auth()->user()->id)) {
            $user_id = auth()->user()->id;
            $role = Role::getRecordById(Role_user::getRecordBYModelId($user_id)->role_id)->name;
        } else {
            $user_id = '';
            $role = '';
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
            $content = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            $data['isContent'] = (isset($content) && !empty($content)) ? true : false;
        } else {
            if (isset($pageContent->txtDescription) && !empty($pageContent->txtDescription)) {
                $data['txtDescription'] = json_encode($pageContent->toArray());
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            $data['pageContent'] = $pageContent;
        }

        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }

        // End CMS PAGE Front Private, Password Prottected Code

        if (isset($_GET['type']) && !empty($_GET['type'])) {
            $typearr = array();
           
             
            $id = intval(strip_tags($_GET['r_id']));
            $type = strip_tags($_GET['type']);

            $typearr['type'] = $type;
            $companylist = Companies::getRecords();
            $ComplaintServices = ComplaintServices::getRecordById($id);
           

            if(isset($ComplaintServices) && !empty($ComplaintServices)){
            $typearr['ComplaintServices'] = $ComplaintServices;
            $typearr['companylist'] = $companylist;
                 return view('complaint-services::frontview.on-line-complaint-form', $typearr);
            }
            else{
                abort(404);
            }
           
        } else {
            return view('complaint-services::frontview.complaint-services', $data);
        }
    }

    public function store()
    {
        $data = Request::all();

        $messsages = array(
            'first_name.required' => 'Your Name is required.',
            'first_name.handle_xss' => 'Please enter valid input',
            'first_name.no_url' => 'URL is not allowed',
            'first_name.regex' => 'Please Enter Valid Input',
            'complaint_pobox.required' => 'Your PO Box & Physical Address is required',
            'complaint_pobox.handle_xss' => 'Please enter valid input',
            'complaint_pobox.no_url' => 'URL is not allowed',
            'complaint_details.required' => 'Full details of complaint is required',
            'complaint_details.handle_xss' => 'Please enter valid input',
            'complaint_details.valid_input' => 'Please enter valid input',
            'complaint_details.no_url' => 'URL is not allowed',
            'complaint_cresponse.required' => 'Response by Company is required',
            'complaint_cresponse.handle_xss' => 'Please enter valid input',
            'complaint_cresponse.valid_input' => 'Please enter valid input',
            'complaint_cresponse.no_url' => 'URL is not allowed',
            'complaint_email.required' => 'Your Email Address is required',
            'complaint_phoneno.required' => 'Your Telephone Number is required',
            'g-recaptcha-response.required' => 'Captcha is required',
        );

        $rules = array(
            'first_name' => ['required', 'handle_xss', 'no_url','regex:/^[a-z0-9\s]+$/i'],
            'complaint_pobox' => ['required', 'handle_xss', 'no_url'],
            'complaint_email' => 'required|email',
            'complaint_phoneno' => 'required',
            'complaint_details' => ['required', 'handle_xss', 'no_url'],
            'complaint_cresponse' => ['required', 'handle_xss', 'no_url'],
            'g-recaptcha-response' => 'required',
        );

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {

            $complaint_lead = new ComplaintLead;
            $complaint_lead->varTitle = strip_tags($data['first_name']);
            $complaint_lead->varEmail = MyLibrary::getEncryptedString($data['complaint_email']);
            $complaint_lead->varPhoneNo = MyLibrary::getEncryptedString($data['complaint_phoneno']);
            $complaint_lead->varPoBox = strip_tags($data['complaint_pobox']);
            $complaint_lead->varService = strip_tags($data['type']);
            $complaint_lead->complaint_date = date('Y-m-d H:i:s', strtotime($data['date_complaint']));

            $complaint_lead->fkIntCompanyId = strip_tags($data['company_name']);

            $complaint_lead->complaint_details = strip_tags($data['complaint_details']);
            $complaint_lead->company_response = strip_tags($data['complaint_cresponse']);

            $saved_name = "";
            $saved_file_name = "";
            $original_name1 = "";
            if (isset($data['file'])) {
                $in_file = '';
                $in_file = Request::file('file') ? Request::file('file') : array();
                if (!empty($in_file)) {
                    foreach ($in_file as $files) {
                        $error = $files->getError();
                        $original_name = $files->getClientOriginalName();
                        $original_name1 .= $files->getClientOriginalName() . ',';
                        $sourceFilePath = $files->getPathName();
                       $pathval = pathinfo($original_name);
                     
                        $saved_name = "file" . "_" . time() . "_" .  self::clean($pathval['filename']). "." . $pathval['extension'];
                        $saved_file_name .= "file" . "_" . time() . "_" . self::clean($pathval['filename']). "." . $pathval['extension'].',';

                        if ($error == UPLOAD_ERR_OK) {
                            $destinationPath = 'cdn\complaint_documents';
                            if ($this->BUCKET_ENABLED) {
                                Aws_File_helper::putObject($sourceFilePath, 'complaint_documents/', $saved_name);
                            } else {
                                $files->move($destinationPath, $saved_name);
                                $data['attachement'] = url('/') . "cdn\complaint_documents" . $saved_name;
                            }
                        }
                    }

                    $complaint_lead->varFile = $saved_file_name;

                }
            }

            $complaint_lead->varIpAddress = MyLibrary::get_client_ip();
            // dd($complaint_lead->complaint_date);
            $complaint_lead->save();

            $data['savedFiles'] = $complaint_lead->varFile;

            /*Start this code for message*/
            if (!empty($complaint_lead->id)) {
                $recordID = $complaint_lead->id;
                Email_sender::complaintMail($data, $recordID);

                if (Request::ajax()) {
                    return json_encode(['success' => 'We have received your request. We will get back to you shortly.']);
                } else {
                    return redirect()->route('thankyou')->with(['form_submit' => true, 'message' => 'We have received your request. We will get back to you shortly.']);
                }

            } else {
                return redirect('/');
            }

        } else {

            //return contact form with errors
            if (!empty($data['back_url'])) {
                return redirect($data['back_url'] . '#complaint_form')->withErrors($validator)->withInput();
            } else {
                return Redirect::back()->withErrors($validator)->withInput();
            }

        }
    }
 public static function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        return preg_replace('/[^A-Za-z0-9\-.]/', '', $string); // Removes special chars.
    }
}
