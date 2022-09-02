<?php
namespace Powerpanel\Payonline\Controllers;

use App\Helpers\Email_sender;
use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Helpers\PlugNPay;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Role_user;
use Config;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\CmsPage\Models\CmsPage;
use Powerpanel\Payonline\Models\Payonline;
use Powerpanel\RoleManager\Models\Role;
use Request;
use Validator;

class PayonlineController extends FrontController
{   
    protected $paymentTypeList = array();
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->paymentTypeList[5] = 'Type Approval';
        // $this->paymentTypeList[1] = 'Ship or Aircraft Radio License';
        // $this->paymentTypeList[2] = 'Other Radio License';
        // $this->paymentTypeList[3] = 'Fuel Operating or Import Permit';
        // $this->paymentTypeList[4] = 'Other';

        $this->paymentTypeList[6] = 'Ship License';
        $this->paymentTypeList[1] = 'Aircraft License';
        $this->paymentTypeList[2] = 'Other Radio Licenses';
        $this->paymentTypeList[3] = 'Fuel Operating or Import Permit';
        $this->paymentTypeList[5] = 'Type Approval';
        $this->paymentTypeList[4] = 'Others';

    }

    /**
     * This method loads Complaintus list view
     * @return  View
     * @since   2020-01-17
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

        $aliasId = slug::resolve_alias($pagename, $sector_slug);
        if (Request::segment(3) == 'preview') {
            $cmsPageId = Request::segment(2);
            $pageContent = CmsPage::getPageByPageId($cmsPageId, false);
        }else{
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
                $data['PageData'] = FrontPageContent_Shield::renderBuilder($pageContent->txtDescription);
            }
            $data['pageContent'] = $pageContent;
        }

        $deviceType = Config::get('Constant.DEVICE');
        
        
        
        $data['paymentTypeList'] = $this->paymentTypeList;
        $data['breadcrumb'] = $this->breadcrumb;
        $data['deviceType'] = $deviceType;

        return view('payonline::frontview.pay', $data);
    }

    /**
     * This method stores Complaintus leads
     * @param   NA
     * @return  Redirection to Thank You page
     * @since   2020-01-17
     * @author  NetQuick
     */
    public function store(HttpRequest $request)
    {
        $data = $request->all();
        $messsages = array(
            'personalInfo_name.required' => 'Name is required',
            'personalInfo_name.handle_xss' => 'Please enter valid input.',
            'personalInfo_companyname.handle_xss' => 'Please enter valid input.',
          
            'note.handle_xss' => 'Please enter valid input.',
//            'paymentDesc.required'=>'Description is required',
            'paymentDesc.handle_xss'=>'Please enter valid input.',
            'personalInfo_email.required' => 'Email is required.',
            'personalInfo_email.email' => 'Please enter a valid email id.',
            //'paymentInfo_invoice.required' => 'Invoice is required',
            'paymentInfo_amount.required' => 'Amount is required',
            'paymentInfo_currency.required' => 'Currency is required',
            'paymentInfo_cardType.required' => 'Card type is required',
        );

        $rules = array(
            'personalInfo_name' => 'required|handle_xss',
            'personalInfo_email' => 'required|email',
            //'paymentInfo_invoice' => 'required',
            'personalInfo_companyname' =>'handle_xss',
            'note' =>'handle_xss',
            'paymentInfo_amount' => 'required',
            'paymentInfo_currency' => 'required',
            'paymentInfo_cardType' => 'required',
            'cardnumber' => 'required',
            'year' => 'required',
            'month' => 'required',
            'paymentDesc' => 'handle_xss',
            'cvv' => 'required|handle_xss',
        );

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $payonline = new Payonline;

        
            $CardData = array(
                "varCardHolderName" => $data['nameOnCard'],
                "CardType" => $data['paymentInfo_cardType'],
                "CardNumber" => str_replace(' ', '', $data['cardnumber']),
                "CardExpiryMonth" => $data['month'],
                "CardExpiryYear" => $data['year'],
                "CardCVV" => $data['cvv'],
                "Email" => $data['personalInfo_email'],
                "Amount" => $data['paymentInfo_amount'],
                "Currency" => $data['paymentInfo_currency'],
            );
            $pnp_transaction_array = PlugNPay::Pay_Now($CardData);

            $payonline->txnId = (isset($pnp_transaction_array['orderID'])?$pnp_transaction_array['orderID']:'---');
            $payonline->txnStatus = (isset($pnp_transaction_array['FinalStatus'])?$pnp_transaction_array['FinalStatus']:'failed');
            $payonline->name = $data['personalInfo_name'];
            $payonline->companyName = (!empty($data['personalInfo_companyname'])?$data['personalInfo_companyname']:NULL);
            $payonline->email = MyLibrary::getEncryptedString($data['personalInfo_email']);
            $payonline->phone = (isset($data['personalInfo_phone']) && !empty($data['personalInfo_phone'])) ? MyLibrary::getEncryptedString($data['personalInfo_phone']) : NULL;
            $payonline->paymentFor = $data['paymentInfo_payment_for'];
            $payonline->description = (!empty($data['paymentInfo_desc'])?$data['paymentInfo_desc']:NULL);
            $payonline->invoiceNo = NULL; //$data['paymentInfo_invoice'];
            $payonline->amount = $data['paymentInfo_amount'];
            $payonline->currency = $data['paymentInfo_currency'];
            $payonline->cardType = $data['paymentInfo_cardType'];
            $payonline->note = (isset($data['paymentInfo_note']) && !empty($data['paymentInfo_note'])) ? $data['paymentInfo_note'] : '';
            $payonline->payment_date = date('Y-m-d h:i:s');
            $payonline->varIpAddress = MyLibrary::get_client_ip();
            $payonline->save();
            
            /*Start this code for message*/
            if (!empty($payonline->id)) {
                $data['payment_for'] = $this->paymentTypeList[$data['paymentInfo_payment_for']];
                Email_sender::sendPaymentDetails($data, $pnp_transaction_array);
                return redirect('/thankyou')->with(['form_submit' => true, 'message' => 'Your transaction has been completed successful. <br/> Your transaction id is <b>'.$pnp_transaction_array['orderID'].'</b>.<br/> Check your inbox to see transaction details.']);
            } else {
                return redirect('/');
            }

        } else {
            if (!empty($data['back_url'])) {
                return redirect($data['back_url'] . '#cardInfo_form')->withErrors($validator)->withInput();
            } else {
                return Redirect::route('pay-online')->withErrors($validator)->withInput();
            }
        }
    }
}
