<?php

namespace App\Http\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use App\SecuritySettings;
use App\User;
use App\LoginLog;
use App\Helpers\MyLibrary;
use Request;
use DB;
use Config;

class SecuritySettingsController extends PowerpanelController {

    /**
     * Create a new Dashboard controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function index() {
        $iTotalRecords = SecuritySettings::getRecordList()->count();
        $userid = auth()->user()->id;
        $pass_change_dt = User::getRecordById($userid);
        $startDate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . '', strtotime($pass_change_dt['pass_change_dt']));
        $personalemail = MyLibrary::getDecryptedString($pass_change_dt['personalId']);
        if ($personalemail != '') {
            $paremail = MyLibrary::obfuscate_email($personalemail);
        } else {
            $paremail = '';
        }
        $login_data = LoginLog::getRecent_securityRecordbyId($userid);
        $Contry = $login_data['varCity'] . ', ' . $login_data['varState_prov'] . ', ' . $login_data['varCountry_name'];
        $Browser_Platform = $login_data['varBrowser_Platform'];
        $lastlogindate = date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . '', strtotime($login_data['created_at']));
        $this->breadcrumb['title'] = trans('Security');
        return view('powerpanel.securitysettings.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'PasswordLastchanged' => $startDate, 'Contry' => $Contry, 'lastlogindate' => $lastlogindate, 'Browser_Platform' => $Browser_Platform, 'paremail' => $paremail]);
    }

    /**
     * This method destroys Log in multiples
     * @return  Log index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord() {
        $data = Request::get('ids');
        $update = SecuritySettings::deleteRecordsPermanent($data);
        exit;
    }

    public function get_list() {
        $filterArr = [];
        $records = [];
        $records["data"] = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order') [0]['column']) ? Request::get('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns') [$filterArr['orderColumnNo']]['name']) ? Request::get('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order') [0]['dir']) ? Request::get('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));

        $sEcho = intval(Request::get('draw'));

        $arrResults = SecuritySettings::getRecordList($filterArr);
        $iTotalRecords = count($arrResults->toArray());

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

    public function tableData($value) {
        $details = '';
        $records = [];
        $minus = '<span class="glyphicon glyphicon-minus"></span>';
        $category = '';
        if (isset($value->varIpAddress)) {
            $category.='<div class="pro-act-btn"><a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:700,headingText:\'Other Information\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-info"></span></a>';
            $category.='<div class="highslide-maincontent">';
            $category.='<ul>';
            if (isset($value->varContinent_code)) {
                $category.='<li><strong>Continent Code:</strong> ' . $value->varContinent_code . '</li>';
            }
            if (isset($value->varContinent_name)) {
                $category.='<li><strong>Continent Name:</strong> ' . $value->varContinent_name . '</li>';
            }
            if (isset($value->varCountry_code2)) {
                $category.='<li><strong>Country Code2:</strong> ' . $value->varCountry_code2 . '</li>';
            }
            if (isset($value->varCountry_code3)) {
                $category.='<li><strong>Country Code3:</strong> ' . $value->varCountry_code3 . '</li>';
            }
            if (isset($value->varCountry_capital)) {
                $category.='<li><strong>Country Capital:</strong> ' . $value->varCountry_capital . '</li>';
            }
            if (isset($value->varState_prov)) {
                $category.='<li><strong>State:</strong> ' . $value->varState_prov . '</li>';
            }
            if (isset($value->varDistrict)) {
                $category.='<li><strong>District:</strong> ' . $value->varDistrict . '</li>';
            }
            if (isset($value->varCity)) {
                $category.='<li><strong>City:</strong> ' . $value->varCity . '</li>';
            }
            if (isset($value->varZipcode)) {
                $category.='<li><strong>Zipcode:</strong> ' . $value->varZipcode . '</li>';
            }
            if (isset($value->varLatitude)) {
                $category.='<li><strong>Latitude:</strong> ' . $value->varLatitude . '</li>';
            }
            if (isset($value->varLongitude)) {
                $category.='<li><strong>Longitude:</strong> ' . $value->varLongitude . '</li>';
            }
            if (isset($value->varIs_eu)) {
                $category.='<li><strong>Is Eu:</strong> ' . $value->varIs_eu . '</li>';
            }
            if (isset($value->varCalling_code)) {
                $category.='<li><strong>Calling Code:</strong> ' . $value->varCalling_code . '</li>';
            }
            if (isset($value->varCountry_tld)) {
                $category.='<li><strong>Country Tld:</strong> ' . $value->varCountry_tld . '</li>';
            }
            if (isset($value->varLanguages)) {
                $category.='<li><strong>Languages:</strong> ' . $value->varLanguages . '</li>';
            }
            $category.='<ul>';
            $category.='</div>';
            $category.='</div>';
        } else {
            $category .= $minus;
        }
        if ($value->ChrBlock != 'Y') {
            $Button = '<button onclick = \'Block("' . $value->id . '")\' title = "Click to Block" class="btn-xs btn red btn-outline right_bottom_btn"><i class="fa fa-ban"></i> Block</button>';
        } else {
            $Button = '<button onclick = \'UnBlock("' . $value->id . '")\' title = "Click to Unblock" class="btn-xs btn btn-outline green right_bottom_btn"><i class="fa fa-unlock"></i>Unblock</button>';
        }
        $records = array(
            '<img class="country-icon" src="' . $value->varCountry_flag . '" alt="' . $value->varCountry_name . '" title="' . $value->varCountry_name . '">',
            $value->varCountry_name,
            $value->varIpAddress,
            $category,
            date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' h:i A', strtotime($value->updated_at)),
            $Button
//            '<a class = "delete" title = "Click to Un-block" data-controller="SecuritySettingsController" data-alias = "' . $value->id . '"><i class="ri-lock-2-line"></i></a>'
        );
        return $records;
    }

}
