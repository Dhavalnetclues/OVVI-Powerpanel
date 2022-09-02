<?php

/**
 * The FormBuilderController class handels subscription functions for front end
 * configuration  process.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.00
 * @since     2017-11-10
 * @author    NetQuick
 */

namespace Powerpanel\FormBuilder\Controllers;

use App\Http\Controllers\FrontController;
use App\Helpers\Email_sender;
use App\Http\Controllers\Controller;
use Powerpanel\FormBuilderLead\Models\FormBuilderLead;
use Powerpanel\Workflow\Models\WorkflowLog;
use Auth;
use Crypt;
use DB;
//use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Redirect;
use Request;
use Validator;
use Config;
use App\Helpers\MyLibrary;
use App\Helpers\time_zone;
use App\UserNotification;

class FormBuilderController extends FrontController {

    public function __construct() {
        parent::__construct();
    }

    /**
     * This method handels send subscribe email function
     * @return  View
     * @since   2017-11-10
     * @author  NetQuick
     */
    public function store() {
        time_zone::time_zone();
        $data = Request::all();
        $customeformdata = \App\CommonModel::getFormBuilderData($data['fkformbuilderid']);
        $json_customeformdata = (json_decode($customeformdata->varFormDescription));

        $rules = array();
        $messsages = array();
        foreach ($json_customeformdata as $jdata) {
            if (isset($jdata->className) && $jdata->className == 'predefine') {
            //    if ($jdata->required == '1') {
            //        $messsages[$jdata->name . '.required'] = $jdata->label . ' field is required';
            //        $rules[$jdata->name] = 'required';
            //    }
            } else {
                if ($jdata->type == 'checkbox-group') {
                    if ($jdata->required == '1') {
                        $messsages[$jdata->name . '.required'] = $jdata->label . ' field is required';
                        $rules[$jdata->name] = 'required';
                    }
                }
                if ($jdata->type == 'radio-group') {
                    if ($jdata->required == '1') {
                        $messsages[$jdata->name . '.required'] = $jdata->label . ' field is required';
                        $rules[$jdata->name] = 'required';
                    }
                }
                if ($jdata->type == 'file') {
                    if ($jdata->required == '1') {
                        $messsages[$jdata->name . '.required'] = $jdata->label . ' field is required';
                        $rules[$jdata->name] = 'required';
                    }
                }
                if ($jdata->type == 'select') {
                    if ($jdata->required == '1') {
                        $messsages[$jdata->name . '.required'] = $jdata->label . ' field is required';
                        $rules[$jdata->name] = 'required';
                    }
                }
                if ($jdata->type == 'radio-group') {
                    if ($jdata->required == '1') {
                        $messsages[$jdata->name . '.required'] = $jdata->label . ' field is required';
                        $rules[$jdata->name] = 'required';
                    }
                }
                if ($jdata->type == 'text') {
                    if (isset($jdata->className) && $jdata->className == 'form-control urlclass') {
                        if ($jdata->required == '1') {
                            $messsages[$jdata->name . '.handle_xss'] = 'Please enter valid input';
                            $rules[$jdata->name] = 'handle_xss';
                        }
                    } else {
                        if ($jdata->required == '1') {
                            if (isset($jdata->subtype) && $jdata->subtype == 'url') {
                                $messsages[$jdata->name . '.required'] = $jdata->label . ' field is required';
                                $messsages[$jdata->name . '.handle_xss'] = 'Please enter valid input';
                                $rules[$jdata->name] = 'required|handle_xss';
                            } else {
                                $messsages[$jdata->name . '.required'] = 'This field is required';
                                $messsages[$jdata->name . '.no_url'] = 'URL is not allowed';
                                $messsages[$jdata->name . '.handle_xss'] = 'Please enter valid input';
                                $rules[$jdata->name] = 'required|handle_xss|no_url';
                            }
                        } else {
                            if (isset($jdata->subtype) && $jdata->subtype == 'url') {
                                $messsages[$jdata->name . '.handle_xss'] = 'Please enter valid input';
                                $rules[$jdata->name] = 'handle_xss';
                            } else {
                                $messsages[$jdata->name . '.no_url'] = 'URL is not allowed';
                                $messsages[$jdata->name . '.handle_xss'] = 'Please enter valid input';
                                $rules[$jdata->name] = 'handle_xss|no_url';
                            }
                        }
                    }
                }
                if ($jdata->type == 'textarea') {
                    if ($jdata->required == '1') {
                        if (isset($jdata->subtype) && $jdata->subtype == 'quill') {
                            if (isset($jdata->subtype) && $jdata->subtype == 'tinymce') {
                                $messsages[$jdata->name . '.required'] = $jdata->label . ' field is required';
                                $rules[$jdata->name] = 'required';
                            }
                        } else if ($jdata->subtype == 'textarea') {
                            $messsages[$jdata->name . '.required'] = 'This field is required';
                            $messsages[$jdata->name . '.no_url'] = 'URL is not allowed';
                            $messsages[$jdata->name . '.handle_xss'] = 'Please enter valid input';
                            $rules[$jdata->name] = 'required|handle_xss|no_url';
                        }
                    } else {
                        if ($jdata->subtype == 'textarea') {
                            $messsages[$jdata->name . '.no_url'] = 'URL is not allowed';
                            $messsages[$jdata->name . '.handle_xss'] = 'Please enter valid input';
                            $rules[$jdata->name] = 'handle_xss|no_url';
                        }
                    }
                }
            }
        }
        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $varIpAddress = MyLibrary::get_client_ip();
            if (isset($data)) {
                $formdata = FormBuilderLead::insertformdata($data, $varIpAddress);
            }
//            MyLibrary::SendNotificationData(strip_tags($customeformdata->varName),'New Inquiry has been received Custom Form Builder');
            
            $filedata = FormBuilderLead::GetFormData($formdata);

            if (!empty($formdata)) {
                $recordID = $formdata;
                Email_sender::formbuilder_email($data, $formdata, $customeformdata, $filedata->filename);
                if (Request::ajax()) {
                    return json_encode(['success' => $customeformdata->varThankYouMsg]);
                } else {
                    return redirect()->route('thankyou')->with(['form_submit' => true, 'message' => $customeformdata->varThankYouMsg]);
                }
            } else {
                return redirect('/');
            }
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public static function Statecmb() {
        $data = DB::table('state')
                ->select('*')
                ->where('fk_country', $_REQUEST['country_id'])
                ->get();

        $combo = "<select name='states' id='states'>";
        $combo .= "<option value=''>- Select State - </option>";
        foreach ($data as $row) {
            $combo .= '<option value=' . $row->id . '>' . htmlspecialchars($row->var_name) . '</option>';
        }
        $combo .= "</select>";
        return $combo;
    }

}
