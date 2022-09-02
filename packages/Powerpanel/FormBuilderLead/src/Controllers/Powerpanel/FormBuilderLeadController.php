<?php

namespace Powerpanel\FormBuilderLead\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use Request;
use Excel;
use App\Department;
use Powerpanel\FormBuilderLead\Models\FormBuilderLead;
use Powerpanel\FormBuilderLead\Models\FormBuilderLeadExport;
use App\CommonModel;
use App\Helpers\MyLibrary;
use Config;
use App\UserNotification;
use App\Helpers\Email_sender;
use Illuminate\Support\Facades\Validator;

class FormBuilderLeadController extends PowerpanelController {


    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }


    public function index() {
        $iTotalRecords = FormBuilderLead::getRecordCount(false,true,"Powerpanel\FormBuilderLead\Models\FormBuilderLead");
        $this->breadcrumb['title'] = trans('formbuilderlead::template.formbuilderleadModule.manageformbuilderLeads');
        return view('formbuilderlead::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb]);
    }


    public function get_list() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order') [0]['column']) ? Request::get('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns') [$filterArr['orderColumnNo']]['name']) ? Request::get('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order') [0]['dir']) ? Request::get('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['start'] = !empty(Request::get('rangeFilter')['from']) ? Request::get('rangeFilter')['from'] : '';
        $filterArr['end'] = !empty(Request::get('rangeFilter')['to']) ? Request::get('rangeFilter')['to'] : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $sEcho = intval(Request::get('draw'));

        if (isset($_REQUEST['id'])) {
            $id = $_REQUEST['id'];
        } else {
            $id = '';
        }

        $arrResults = FormBuilderLead::getRecordList($filterArr, $id);
        $iTotalRecords = FormBuilderLead::getRecordCount($filterArr, true, '', '', $id);
        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (!empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                $records["data"][] = $this->tableData($value);
            }
        }

        if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
            $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
        }

        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }



    public function DeleteRecord(Request $request) {
        $data = Request::all('ids');
        $update = MyLibrary::deleteMultipleRecords($data,false,false,"Powerpanel\FormBuilderLead\Models\FormBuilderLead");
        UserNotification::deleteNotificationByRecordID($data['ids'], Config::get('Constant.MODULE.ID'));
        echo json_encode($update);
        exit;
    }



    public function ExportRecord() {
        return Excel::download(new FormBuilderLeadExport, Config::get('Constant.SITE_NAME') . '-' . trans("formbuilderlead::template.formbuilderleadModule.formbuilderleads") . '-' . date("dmy-h:i") . '.xlsx');
    }



    public static function tableData($value, $page=false) {

        // Checkbox
        $checkboxFirstTD = view('powerpanel.partials.checkbox', ['name'=>'delete[]', 'value'=>$value->id])->render();

        $customeformdata = \App\CommonModel::getFormBuilderData($value->fk_formbuilder_id);
        $details = '';
        $label = '';
        $requestkey_array = [];
        $json_customeformdata = (isset($customeformdata->varFormDescription)) ? (json_decode($customeformdata->varFormDescription)) : null;
        $json_data = (json_decode($value->formdata));
        $json_Array = (array) $json_data;

        foreach ($json_data as $key => $va) {
            $requestkey_array[] = $key;
        }

        $requestKeys = $requestkey_array;
        $inputsOfEmailArray = array();
        $valueindex = 0;
        $checkbox = '';
        $user_email = '';

        if(!empty($json_customeformdata)){
	        foreach ($json_customeformdata as $key => $val) {
	            if (isset($val->name) && in_array($val->name, $requestKeys)) {
	                if (isset($val->type)) {
	                    $inputsOfEmailArray[$valueindex]['type'] = $val->type;
	                }
	                if (isset($val->label)) {
	                    $inputsOfEmailArray[$valueindex]['label'] = $val->label;
	                }
	                if (isset($val->subtype)) {
	                    $inputsOfEmailArray[$valueindex]['subtype'] = $val->subtype;
	                }
	                if (isset($val->className)) {
	                    $inputsOfEmailArray[$valueindex]['className'] = $val->className;
	                }

	                if (isset($val->type) && $val->type == 'checkbox-group') {
	                    $selctedchkvalues = array();
	                    foreach ($json_Array[$val->name] as $chkvalue) {
	                        $chklabel = MyLibrary::getLabelforformbuilder($chkvalue, $val->values);
	                        if (!empty($chklabel)) {
	                            array_push($selctedchkvalues, $chklabel);
	                        }
	                    }
	                    $checkbox = implode(",", $selctedchkvalues);
	                    $inputsOfEmailArray[$valueindex]['value'] = $checkbox;
	                } else if (isset($val->type) && $val->type == 'radio-group') {
	                    $chklabel = MyLibrary::getLabelforformbuilder($json_Array[$val->name], $val->values);
	                    $checkbox = $chklabel;
	                    $inputsOfEmailArray[$valueindex]['value'] = $checkbox;
	                } else if (isset($val->type) && $val->type == 'select') {
	                    $chklabel = MyLibrary::getLabelforformbuilder($json_Array[$val->name], $val->values);
	                    $checkbox = $chklabel;
	                    $inputsOfEmailArray[$valueindex]['value'] = $checkbox;
	                } elseif (isset($val->type) && $val->type == 'textarea') {
	                    if (isset($val->subtype) && $val->subtype == 'tinymce') {
	                        $inputsOfEmailArray[$valueindex]['value'] = $json_Array[$val->name];
	                    } else if (isset($val->subtype) && $val->subtype == 'quill') {
	                        $inputsOfEmailArray[$valueindex]['value'] = nl2br($json_Array[$val->name]);
	                    } else {
	                        $inputsOfEmailArray[$valueindex]['value'] = nl2br($json_Array[$val->name]);
	                    }
	                }else if (isset($val->className) && $val->className == 'form-control urlclass') {
	                    $url = $json_Array[$val->name];
	                    $currenturl = explode("/", $url);
	                    if (isset($currenturl[2])) {
	                        $wwwurl = explode(".", $currenturl[2]);
	                    } else {
	                        $wwwurl[0] = '1';
	                    }
	                    if (isset($currenturl[0]) && $currenturl[0] != 'http:' && $currenturl[0] != 'https:') {
	                        $url_1 = 'http://';
	                    } else {
	                        $url_1 = '';
	                    }
	                    if (isset($wwwurl[0]) && $wwwurl[0] == '1') {
	                        $url_2 = 'www.';
	                    } else {
	                        $url_2 = '';
	                    }
	                    $inputsOfEmailArray[$valueindex]['value'] = $url_1 . $url_2 . $json_Array[$val->name];
	                } elseif (isset($val->subtype) && $val->subtype == 'email') {
	                    $user_email = $json_Array[$val->name];
	                    $inputsOfEmailArray[$valueindex]['value'] = $user_email;
	                } elseif (isset($val->type) && $val->type == 'text') {
	                    if (isset($val->subtype) && $val->subtype == 'color') {
	                        $inputsOfEmailArray[$valueindex]['value'] = $json_Array[$val->name] . '  <div style="width: 137px;height: 30px;background-color: ' . $json_Array[$val->name] . ';"></div>';
	                    } else {
	                        $inputsOfEmailArray[$valueindex]['value'] = $json_Array[$val->name];
	                    }
	                }   else {
	                    $inputsOfEmailArray[$valueindex]['value'] = $json_Array[$val->name];
	                }

	                $valueindex++;
	            } else if (isset($val->type) && $val->type == 'checkbox-group') {

	                foreach ($val->values as $chkvalue) {
	                    if (isset($json_Array[$chkvalue->value])) {
	                        $inputsOfEmailArray[$valueindex]['type'] = '';
	                        if ($chkvalue->label == 'Country' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
	                            $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
	                            $cname = MyLibrary::getEmailCountry($json_Array[$chkvalue->value]);
	                            $name = $cname[0]->var_name;
	                        } else if ($chkvalue->label == 'State' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
	                            $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
	                            $sname = MyLibrary::getEmailState($json_Array[$chkvalue->value]);
	                            $name = $sname[0]->var_name;
	                        } else if ($chkvalue->label == 'Gender' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
	                            $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
	                            if ($json_Array[$chkvalue->value] == 'male') {
	                                $name = 'Male';
	                            } else if ($json_Array[$chkvalue->value] == 'female') {
	                                $name = 'Female';
	                            } else if ($json_Array[$chkvalue->value] == 'transgender') {
	                                $name = 'Trans Gender';
	                            }
	                        } else if ($chkvalue->label == 'Month' && (isset($chkvalue->selected) && $chkvalue->selected == 1)) {
	                            $inputsOfEmailArray[$valueindex]['label'] = $chkvalue->label;
	                            if ($json_Array[$chkvalue->value] == '01') {
	                                $name = 'January';
	                            } else if ($json_Array[$chkvalue->value] == '02') {
	                                $name = 'February';
	                            } else if ($json_Array[$chkvalue->value] == '03') {
	                                $name = 'March';
	                            } else if ($json_Array[$chkvalue->value] == '04') {
	                                $name = 'April';
	                            } else if ($json_Array[$chkvalue->value] == '05') {
	                                $name = 'May';
	                            } else if ($json_Array[$chkvalue->value] == '06') {
	                                $name = 'June';
	                            } else if ($json_Array[$chkvalue->value] == '07') {
	                                $name = 'July';
	                            } else if ($json_Array[$chkvalue->value] == '08') {
	                                $name = 'August';
	                            } else if ($json_Array[$chkvalue->value] == '09') {
	                                $name = 'September';
	                            } else if ($json_Array[$chkvalue->value] == '10') {
	                                $name = 'October';
	                            } else if ($json_Array[$chkvalue->value] == '11') {
	                                $name = 'November';
	                            } else if ($json_Array[$chkvalue->value] == '12') {
	                                $name = 'December';
	                            }
	                        } else {
	                            $name = '';
	                        }
	                        $inputsOfEmailArray[$valueindex]['value'] = $name;
	                        $valueindex++;
	                    }
	                }
	            }
	        }
        }

        if (!empty($inputsOfEmailArray)) {
            foreach ($inputsOfEmailArray as $input_value) {

                if (isset($input_value)) {
                    if (isset($input_value['label'])) {
                        if (isset($input_value['subtype']) && $input_value['subtype'] == 'email') {
                            $label .= '<b>' . $input_value['label'] . '</b>' . ' :- ';
                            $label .= '<a href="mailto:' . $input_value['value'] . '">' . $input_value['value'] . '</a>';
                            $label .= '<div></div>';
                        } else if (isset($input_value['subtype']) && $input_value['subtype'] == 'url') {
                            $label .= '<b>' . $input_value['label'] . '</b>' . ' :- ';
                            $label .= '<a href="' . $input_value['value'] . '" target="_blank">' . $input_value['value'] . '</a>';
                            $label .= '<div></div>';
                        } else if (isset($input_value['className']) && $input_value['className'] == 'form-control urlclass') {
                            $label .= '<b>' . $input_value['label'] . '</b>' . ' :- ';
                            $label .= '<a href="' . $input_value['value'] . '" target="_blank">' . $input_value['value'] . '</a>';
                            $label .= '<div></div>';
                        } else if (isset($input_value['type']) && $input_value['type'] == 'file') {
                            if (Config::get('Constant.BUCKET_ENABLED')) {
                                $Url = Config::get('Constant.CDN_PATH') . "foi_documents/" . $value->filename;
                            } else {
                                $Url = url('/') . "/cdn/foi_documents/" . $value->filename;
                            }
                            $label .= '<b>File Name</b>' . ' :- ';
                            $label .= $value->filename . '  <a href="' . $Url . '" download><i class="fa fa-download" aria-hidden="true"></i></a>';
                            $label .= '<div></div>';
                        } else {
                            $label .= '<b>' . $input_value['label'] . '</b>' . ' :- ';
                            if ($input_value['value'] != '') {
                                $label .= $input_value['value'];
                            } else {
                                $label .= 'N/A';
                            }
                            $label .= '<div></div>';
                        }
                    }
                }

            }

            $details .= '<div class="pro-act-btn">';
            if($page == 'dashboard') {
                $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-feedback-line fs-24 body-color"></i></a>';
            } else {
                $details .= '<a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Contents\',wrapperClassName:\'titlebar\',showCredits:false});"><i class="ri-mail-open-line fs-16"></i></a>';
            }
            $details .= '<div class="highslide-maincontent">' . nl2br($label) . '</div>';
            $details .= '</div>';
        } else {
            $details .= '-';
        }
        
        $receive_date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';
        
        $records = array(
            $checkboxFirstTD,
            (isset($customeformdata->varName)) ? $customeformdata->varName : "N/A",
            (isset($customeformdata->varEmail)) ? $customeformdata->varEmail : "N/A",
            $details,
            $value->varIpAddress,
            $receive_date
        );

        return $records;
    }

}
