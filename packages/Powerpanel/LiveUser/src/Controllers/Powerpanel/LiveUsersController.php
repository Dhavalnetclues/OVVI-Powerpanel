<?php

namespace Powerpanel\LiveUser\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Powerpanel\LiveUser\Models\LiveUsers;
use Request;
use DB;
use Config;
use Excel;
use App\Helpers\MyLibrary;
use Powerpanel\LiveUser\Exports\LiveUserExport;
use App\CommonModel;

class LiveUsersController extends PowerpanelController {

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
        $iTotalRecords = LiveUsers::getRecordList()->count();
        $this->breadcrumb['title'] = trans('Live Users');
        $arrResultsCountry = LiveUsers::getRecordWithCountry();

        return view('liveuser::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'arrResultsCountry' => $arrResultsCountry]);
    }

    /**
     * This method destroys Log in multiples
     * @return  Log index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord() {
        $data = Request::get('ids');
        $update = LiveUsers::deleteRecordsPermanent($data);
        exit;
    }

    public function BlockRecord() {
        $data = Request::get('ids');
        $update = LiveUsers::blockRecordsPermanent($data);
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
        $filterArr['CounFilter'] = !empty(Request::get('CounValue')) ? Request::get('CounValue') : '';
        $filterArr['Before15Day'] = !empty(Request::get('Before15Day')) ? Request::get('Before15Day') : '';
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $filterArr['rangeFilter'] = !empty(Request::input('rangeFilter')) ? Request::input('rangeFilter') : '';
        $filterArr['start'] = !empty(Request::get('rangeFilter')['from']) ? Request::get('rangeFilter')['from'] : '';
        $filterArr['end'] = !empty(Request::get('rangeFilter')['to']) ? Request::get('rangeFilter')['to'] : '';

        $sEcho = intval(Request::get('draw'));

        $arrResults = LiveUsers::getRecordList($filterArr);
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
            $category .= '<div class="pro-act-btn"><a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:700,headingText:\'Other Information\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-info"></span></a>';
            $category .= '<div class="highslide-maincontent">';
            $category .= '<ul>';
            if (isset($value->varContinent_code)) {
                $category .= '<li><strong>Continent Code:</strong> ' . $value->varContinent_code . '</li>';
            }else{
                $category .= '<li><strong>Continent Code:</strong> No Available </li>';
            }
            if (isset($value->varContinent_name)) {
                $category .= '<li><strong>Continent Name:</strong> ' . $value->varContinent_name . '</li>';
            }else{
                $category .= '<li><strong>Continent Name:</strong> No Available </li>';
            }
            if (isset($value->varCountry_code2)) {
                $category .= '<li><strong>Country Code2:</strong> ' . $value->varCountry_code2 . '</li>';
            }else{
                $category .= '<li><strong>Country Code2:</strong> No Available </li>';
            }
            if (isset($value->varCountry_code3)) {
                $category .= '<li><strong>Country Code3:</strong> ' . $value->varCountry_code3 . '</li>';
            }else{
                $category .= '<li><strong>Country Code3:</strong> No Available </li>';
            }
            if (isset($value->varCountry_capital)) {
                $category .= '<li><strong>Country Capital:</strong> ' . $value->varCountry_capital . '</li>';
            }else{
                $category .= '<li><strong>Country Capital:</strong>  No Available </li>';
            }
            if (isset($value->varState_prov)) {
                $category .= '<li><strong>State:</strong> ' . $value->varState_prov . '</li>';
            }else{
                $category .= '<li><strong>State:</strong> No Available </li>';
            }
            if (isset($value->varDistrict)) {
                $category .= '<li><strong>District:</strong> ' . $value->varDistrict . '</li>';
            }else{
                $category .= '<li><strong>District:</strong> No Available </li>';
            }
            if (isset($value->varCity)) {
                $category .= '<li><strong>City:</strong> ' . $value->varCity . '</li>';
            }else{
                $category .= '<li><strong>City:</strong>  No Available </li>';
            }
            if (isset($value->varZipcode)) {
                $category .= '<li><strong>Zipcode:</strong> ' . $value->varZipcode . '</li>';
            }else{
                $category .= '<li><strong>Zipcode:</strong>  No Available </li>';
            }
            if (isset($value->varLatitude)) {
                $category .= '<li><strong>Latitude:</strong> ' . $value->varLatitude . '</li>';
            }else{
                $category .= '<li><strong>Latitude:</strong>  No Available </li>';
            }
            if (isset($value->varLongitude)) {
                $category .= '<li><strong>Longitude:</strong> ' . $value->varLongitude . '</li>';
            }else{
                $category .= '<li><strong>Longitude:</strong>  No Available </li>';
            }
            if (isset($value->varIs_eu)) {
                $category .= '<li><strong>Is Eu:</strong> ' . $value->varIs_eu . '</li>';
            }else{
                $category .= '<li><strong>Is Eu:</strong>  No Available </li>';
            }
            if (isset($value->varCalling_code)) {
                $category .= '<li><strong>Calling Code:</strong> ' . $value->varCalling_code . '</li>';
            }else{
                $category .= '<li><strong>Calling Code:</strong>  No Available </li>';
            }
            if (isset($value->varCountry_tld)) {
                $category .= '<li><strong>Country Tld:</strong> ' . $value->varCountry_tld . '</li>';
            }else{
                $category .= '<li><strong>Country Tld:</strong>  No Available </li>';
            }
            $category .= '<ul>';
            $category .= '</div>';
            $category .= '</div>';
        } else {
            $category .= $minus;
        }
        if ($value->ChrBlock != 'Y') {
            $Button = '<button onclick = \'Block("' . $value->id . '")\' title = "Click to Block" class="btn-xs btn red btn-outline right_bottom_btn"><i class="fa fa-ban"></i> Block</button>';
        } else {
            $Button = '<button onclick = \'UnBlock("' . $value->id . '")\' title = "Click to Unblock" class="btn-xs btn btn-outline green right_bottom_btn"><i class="fa fa-unlock"></i>Unblock</button>';
        }
        $records = array(
            ($value->ChrBlock == 'Y') ? '' : '<input type="checkbox" name="block[]" class="blkDelete" value="' . $value->id . '">',
            '<img class="country-icon" src="' . $value->varCountry_flag . '" alt="-" title="' . $value->varCountry_name . '">',
            ($value->varCountry_name != '') ? $value->varCountry_name : "Unknown",
            $value->varIpAddress,
            $category,
            date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->updated_at)),
            $Button
        );
        return $records;
    }

    function block_user() {
        $record = Request::all();
        DB::table('live_user')
                ->where('id', $record['id'])
                ->update(['ChrBlock' => 'Y']);
    }

    function un_block_user() {
        $record = Request::all();
        DB::table('live_user')
                ->where('id', $record['id'])
                ->update(['ChrBlock' => 'N']);
    }

    public function ExportRecord()
    {
        return Excel::download(new LiveUserExport, Config::get('Constant.SITE_NAME') . '-' . 'LiveUsersList' . '-' . date("dmy-h:i") . '.xlsx');
    }
}
