<?php
namespace Powerpanel\ComplaintLead\Controllers;

use App\Http\Controllers\FrontController;
use Powerpanel\ContactInfo\Models\ContactInfo;
use Powerpanel\ComplaintLead\Models\ComplaintLead;
use App\Helpers\Email_sender;
use App\Helpers\MyLibrary;
use App\NewsletterLead;
use App\Rules\ValidateBadWord;
use App\Rules\ValidRecaptcha;
use Config;
use Crypt;
use File;
use Illuminate\Support\Facades\Redirect;
use Request;
use Validator;

class ComplaintleadController extends FrontController
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
     * This method loads Complaintus list view
     * @return  View
     * @since   2020-01-17
     * @author  NetQuick
     */
    public function index()
    {
        $deviceType = Config::get('Constant.DEVICE');
        $complaints = ContactInfo::getContactList();
        $data = ['complaint_info' => $complaints,
            'breadcrumb' => $this->breadcrumb,
            'deviceType' => $deviceType];

        return view('complaintlead::frontview.complaint', $data);
    }

    /**
     * This method stores Complaintus leads
     * @param   NA
     * @return  Redirection to Thank You page
     * @since   2020-01-17
     * @author  NetQuick
     */
    public function store()
    {
        $data = Request::all();

        $messsages = array(
            'first_name.required' => 'Name field is required',
            'first_name.handle_xss' => 'Please enter valid input',
            'first_name.no_url' => 'URL is not allowed',
            'user_message.handle_xss' => 'Please enter valid input',
            'user_message.valid_input' => 'Please enter valid input',
            'user_message.no_url' => 'URL is not allowed',
            'complaint_email.required' => 'Email is required',
            'g-recaptcha-response.required' => 'Captcha is required',
            'phone_number.required' => 'Phone is required',
        );

        $rules = array(
            'first_name' => ['required', 'handle_xss', 'no_url', new ValidateBadWord],
            'complaint_email' => 'required|email',
            'user_message' => ['handle_xss', 'no_url', new ValidateBadWord],
        );

        if (isset($data['phone_number'])) {
            $rules['phone_number'] = 'required';
        }

        $rules['g-recaptcha-response'] = ['required', new ValidRecaptcha];

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $complaint_lead = new ComplaintLead;
            $complaint_lead->varTitle = strip_tags($data['first_name']);
            $complaint_lead->varEmail = MyLibrary::getEncryptedString($data['complaint_email']);
            if (isset($data['phone_number'])) {
                $complaint_lead->varPhoneNo = MyLibrary::getEncryptedString($data['phone_number']);
            } else {
                $complaint_lead->varPhoneNo = '';
            }
            if (isset($data['user_message'])) {
                $complaint_lead->txtUserMessage = MyLibrary::getEncryptedString(strip_tags($data['user_message']));
            } else {
                $complaint_lead->txtUserMessage = '';
            }
            if (isset($data['service_id'])) {
                $complaint_lead->fkIntServiceId = $data['service_id'];
            } else {
                $complaint_lead->fkIntServiceId = null;
            }
            $complaint_lead->varIpAddress = MyLibrary::get_client_ip();
            $complaint_lead->save();

            /*Start this code for message*/
            if (!empty($complaint_lead->id)) {
                $recordID = $complaint_lead->id;
                Email_sender::complaint($data, $complaint_lead->id);

                if (File::exists(app_path() . '/NewsletterLead.php')) {
                    if (isset($data['subscribe']) && $data['subscribe'] == "on") {

                        $emalExists = NewsletterLead::getRecords()->publish()->deleted()->where('varEmail', "=", Mylibrary::getEncryptedString($data['complaint_email']))->first();
                        if (empty($emalExists)) {
                            $subscribeArr = [];
                            $subscribeArr['varEmail'] = Mylibrary::getEncryptedString($data['complaint_email']);
                            $subscribeArr['varTitle'] = strip_tags($data['first_name']);
                            $subscribeArr['varIpAddress'] = MyLibrary::get_client_ip();
                            $subscribeArr['created_at'] = date('Y-m-d h:i:s');
                            $subscribe = NewsletterLead::insertGetId($subscribeArr);

                            $newsLetterData = NewsletterLead::getRecords()->publish()->deleted()->checkRecordId($subscribe);
                            if ($newsLetterData->count() > 0) {
                                $newsLetterData = $newsLetterData->first()->toArray();
                                $id = Crypt::encrypt($newsLetterData['id']);
                                Email_sender::newsletter($newsLetterData, $id);
                            }

                        } else {

                            if ($emalExists->chrSubscribed == "N") {
                                $newsLetterData = $emalExists->toArray();
                                $id = Crypt::encrypt($newsLetterData['id']);
                                Email_sender::newsletter($newsLetterData, $id);
                            }
                        }
                    }
                }

                if (Request::ajax()) {
                    return json_encode(['success' => 'Thank you for complainting us, We will get back to you shortly.']);
                } else {
                    return redirect()->route('thank-you')->with(['form_submit' => true, 'message' => 'Thank you for complainting us, We will get back to you shortly.']);
                }

            } else {
                return redirect('/');
            }

        } else {

            //return complaint form with errors
            if (!empty($data['back_url'])) {
                return redirect($data['back_url'] . '#complaint_form')->withErrors($validator)->withInput();
            } else {
                return Redirect::route('complaint')->withErrors($validator)->withInput();
            }

        }
    }
}
