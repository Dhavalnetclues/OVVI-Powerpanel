<?php
namespace Powerpanel\ContactUsLead\Controllers;

use App\Helpers\Email_sender;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Config;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\ContactInfo\Models\ContactInfo;
use Powerpanel\ContactUsLead\Models\ContactLead;
use Powerpanel\RoleManager\Models\Role;
use Request;
use Validator;

class ContactleadController extends FrontController
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
     * This method loads Contactus list view
     * @return  View
     * @since   2020-01-17
     * @author  NetQuick
     */
    public function create()
    {
        $data = array();
        $primaryContact = ContactInfo::Primary_ContactInfo();
        $pagename = Request::segment(1);
        if (is_numeric($pagename) && (int) $pagename > 0) {
            $aliasId = $pagename;
        } else {
            $aliasId = slug::resolve_alias($pagename);
        }

        if (Request::segment(3) == 'preview') {
            $cmsPageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($cmsPageId, false);
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
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription)['response'];
            }
            $data['pageContent'] = $pageContent;
        }
        if (isset($pageContent->varTitle) && !empty($pageContent->varTitle)) {
            view()->share('detailPageTitle', $pageContent->varTitle);
        }
        // End CMS PAGE Front Private, Password Prottected Code
        $deviceType = Config::get('Constant.DEVICE');
        $contacts['primary'] = ContactInfo::getFrontContactDetails('Y');
        $contacts['non-primary'] = ContactInfo::getFrontContactDetails('N');
        $data = [
            'primaryContact'=> $primaryContact,
            'contact_info' => $contacts,
            'breadcrumb' => $this->breadcrumb,
            'data' => $data,
            'deviceType' => $deviceType,
        ];
        return view('contactuslead::frontview.contact-us', $data);
    }

    /**
     * This method stores Contactus leads
     * @param   NA
     * @return  Redirection to Thank You page
     * @since   2020-01-17
     * @author  NetQuick
     */
    public function store()
    {
        $data = Request::all();

        $messsages = array(
            'first_name.required' => 'First Name is required',
            'first_name.regex' => 'Please Enter Valid Input',
            'email' => 'Please enter valid email',
            'message' => 'Please enter valid input',
            'phone' => 'Please enter your phone nomber',
            'g-recaptcha-response.required' => 'Captcha is required',
        );

        $rules = array(
            'first_name' => 'required|handle_xss|regex:/^[a-z0-9\s]+$/i',
            'email' => 'required|email',
            'phone' => 'required|handle_xss',
            'message' => 'handle_xss',
            'g-recaptcha-response' => 'required',
        );

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $contactus_lead = new ContactLead;
            $contactus_lead->varTitle = strip_tags($data['first_name']);
            $contactus_lead->varEmail = MyLibrary::getEncryptedString($data['email']);

            if (isset($data['phone'])) {
                $contactus_lead->varPhoneNo = MyLibrary::getEncryptedString($data['phone']);
            } else {
                $contactus_lead->varPhoneNo = '';
            }

            if (isset($data['message'])) {
                $contactus_lead->txtUserMessage = MyLibrary::getEncryptedString(strip_tags($data['message']));
            } else {
                $contactus_lead->txtUserMessage = '';
            }

            $contactus_lead->varIpAddress = MyLibrary::get_client_ip();
            $contactus_lead->save();

            /*Start this code for message*/
            if (!empty($contactus_lead->id)) {
                $recordID = $contactus_lead->id;
                Email_sender::contactUs($data, $recordID);

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
                return redirect($data['back_url'] . '#contactus_form')->withErrors($validator)->withInput();
            } else {
                return Redirect::route('contact-us')->withErrors($validator)->withInput();
            }

        }
    }
}
