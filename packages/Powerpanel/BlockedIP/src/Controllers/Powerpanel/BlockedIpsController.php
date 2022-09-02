<?php
namespace Powerpanel\BlockedIP\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Illuminate\Support\Facades\Redirect;
use Powerpanel\BlockedIP\Models\BlockedIps;
use Request;
use Config;
use App\Log;
use Validator;
use Carbon\Carbon;
use Auth;
use App\CommonModel;
use Cache;
use App\Helpers\MyLibrary;

class BlockedIpsController extends PowerpanelController {

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
        $iTotalRecords = BlockedIps::getRecordList()->count();
        $this->breadcrumb['title'] = trans('Blocked IPs');

//       ==========================
        $filterArr = [];
        $filterArr['orderColumnNo'] = (!empty(Request::get('order') [0]['column']) ? Request::get('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns') [$filterArr['orderColumnNo']]['name']) ? Request::get('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order') [0]['dir']) ? Request::get('order') [0]['dir'] : '');
        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));
        $arrResults = BlockedIps::getRecordList($filterArr);
        return view('blockip::powerpanel.list', ['iTotalRecords' => $iTotalRecords, 'breadcrumb' => $this->breadcrumb, 'arrResults' => $arrResults]);
    }

    /**
     * This method destroys Log in multiples
     * @return  Log index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord() {
        $data = Request::get('ids');
        $update = BlockedIps::deleteRecordsPermanent($data);
        exit;
    }

    public function UpdateData() {
        $data = Request::all();
        $blockingIdArrary = explode(",", $data['blockid']);
        $block_data = [];
        foreach ($blockingIdArrary as $BlockID) {
            if (isset($data['new_link_' . $BlockID])) {
                $block_data['varNewUrl'] = $data['new_link_' . $BlockID];
                $block_data['varUrl'] = $data['old_link_' . $BlockID];
                $whereConditions = ['id' => $BlockID];
                $update = CommonModel::updateRecords($whereConditions, $block_data, '', '\App\BlockedIps');
            }
            self::flushCache();
        }
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

        $isAdmin = false;
        if (isset($this->currentUserRoleData) && !empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $isAdmin = true;
            }
        }

        $ignoreId = [];
        $arrResults = BlockedIps::getRecordList($filterArr, $isAdmin, $ignoreId, $this->currentUserRoleSector);
        $iTotalRecords = count($arrResults->toArray());

        if (!empty($arrResults)) {
            $currentUserID = auth()->user()->id;
            $permit = [
                'canblocked_ipsedit' => Auth::user()->can('blocked_ips-edit'),
                'canblocked_ipspublish' => Auth::user()->can('blocked_ips-publish'),
                'canbblocked_ipsdelete' => Auth::user()->can('blocked_ips-delete'),
                'canblocked_ipsreviewchanges' => Auth::user()->can('blocked_ips-reviewchanges'),
                'canblocked_ipslist' => Auth::user()->can('blocked_ips-list'),
            ];

            foreach ($arrResults as $key => $value) {
                if (!in_array($value->id, $ignoreId)) {
                    $records['data'][] = $this->tableData($value, $permit, $currentUserID);
                }
            }
        }

        if (!empty(Request::input('customActionType')) && Request::input('customActionType') == 'group_action') {
            $records['customActionStatus'] = 'OK';
        }
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    public function edit($alias = false) {
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }

        $templateData = array();
        if (!is_numeric($alias)) {
            $total = BlockedIps::getRecordCount();
            if ($userIsAdmin) {
                $total = $total + 1;
            }
            $this->breadcrumb['title'] = 'Add Blocked IP';
            $this->breadcrumb['module'] = 'Blocked IPs';
            $this->breadcrumb['url'] = 'powerpanel/blocked-ips';
            $this->breadcrumb['inner_title'] = '';
    //            $data = ['total' => $total, 'breadcrumb' => $this->breadcrumb];
            $templateData['total'] = $total;
            $templateData['breadcrumb'] = $this->breadcrumb;
        } else {
            $id = $alias;
            $department = BlockedIps::getRecordById($id);

            if (empty($department)) {
                return redirect()->route('powerpanel.blockedips.add');
            }
            if ($department->fkMainRecord != '0') {

                $department_highLight = BlockedIps::getRecordById($department->fkMainRecord);
                $templateData['department_highLight'] = $department_highLight;
            } else {
                $templateData['department_highLight'] = "";
            }
            $this->breadcrumb['title'] = 'Edit Blocked IP';
            $this->breadcrumb['module'] = 'Blocked IPs';
            $this->breadcrumb['url'] = 'powerpanel/blocked-ips';
            $this->breadcrumb['inner_title'] = $department->varTitle;
//            $data = ['department' => $department, 'id' => $id, 'breadcrumb' => $this->breadcrumb];
            $templateData['department'] = $department;
            $templateData['id'] = $id;
            $templateData['breadcrumb'] = $this->breadcrumb;
        }

        $templateData['userIsAdmin'] = $userIsAdmin;
        return view('blockip::powerpanel.actions', $templateData);
    }

    public function handlePost(Request $request) {
        $userIsAdmin = false;
        if (!empty($this->currentUserRoleData)) {
            if ($this->currentUserRoleData->chrIsAdmin == 'Y') {
                $userIsAdmin = true;
            }
        }
        $postArr = Request::all();

        $messsages = [
            'ip_address.required' => 'IP address field is required.'];
        $rules = [
            'ip_address' => 'required|max:20',
        ];
        $validator = Validator::make($postArr, $rules, $messsages);
        if ($validator->passes()) {
            $blockipArr = [];
            $actionMessage = trans('template.departmentModule.updateMessage');
            $departmentObj = $this->insertNewRecord($postArr, $blockipArr);
            $actionMessage = 'A new ip has been successfully added.';
            return redirect('/powerpanel/blocked-ips')->with('message', $actionMessage);
        } else {
            return Redirect::back()->withErrors($validator)->withInput();
        }
    }

    public function insertNewRecord($postArr, $blockipArr) {
        $location = MyLibrary::get_geolocation($postArr['ip_address']);
        $decodedLocation = json_decode($location, true);
        $response = false;
        $blockipArr['varIpAddress'] = stripslashes(trim($postArr['ip_address']));
        $blockipArr['varCountry_name'] = !empty($decodedLocation['country_name']) ? $decodedLocation['country_name'] : null;
        $blockipArr['varCountry_flag'] = !empty($decodedLocation['country_flag']) ? $decodedLocation['country_flag'] : null;
        $blockipArr['created_at'] = Carbon::now();
        $blockipArr['updated_at'] = Carbon::now();
        for ($x = 1; $x <= 5; $x++) {
            $blockipID = BlockedIps::addRecord($blockipArr);
        }
        if (!empty($blockipID)) {
            $id = $blockipID;
            $logArr = MyLibrary::logData($blockipID, '93', 'add');
            $logArr['varTitle'] = stripslashes(trim($postArr['ip_address']));
            Log::recordLog($logArr);
            $response = $blockipID;
            self::flushCache();
        }
        return $response;
    }

    public static function flushCache() {
        Cache::tags('blocked-ips')->flush();
    }

    public function tableData($value) {

        // Checkbox
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();


        $details = '';
        $phoneNo = '';
        $records = [];

        $category = '';
        $blockipurl = BlockedIps::getRecordIpList($value->varIpAddress);
        if (isset($value->varUrl)) {
            $category .= '<div class="pro-act-btn"><a href="javascript:void(0)" class="" onclick="return hs.htmlExpand(this,{width:500,headingText:\'URLs\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-info"></span></a>';
            $category .= '<div class="highslide-maincontent">';
            $category .= '<ul>';
            $blockipurl = \App\BlockedIps::getRecordIpList($value->varIpAddress);
            foreach ($blockipurl as $blockData) {
                $category .= '<li>';
                $category .= $blockData->varUrl;
                $category .= '</li>';
            }
            $category .= '<ul>';
            $category .= '</div>';
            $category .= '</div>';
        } else {
            $category .= '-';
        }

        if (!empty($value->txtBrowserInf)) {
            $details .= '<div class="pro-act-btn">';
            $details .= '<a href="javascript:void(0)" class="without_bg_icon" onclick="return hs.htmlExpand(this,{width:300,headingText:\'Access Information\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="fa fa-link"></span></a>';
            $details .= '<div class="highslide-maincontent">' . nl2br($value->txtBrowserInf) . '</div>';
            $details .= '</div>';
        } else {
            $details .= '-';
        }
        $icon = '';
        $blockid = '';
        $icon .= '<a href="JavaScript:Void(0);" id="Send_Report_Email" data-id="' . $value->id . '"><i class="fa fa-tasks" ></i></a>';

        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        $records = array(
            $checkbox,
            '<img class="country-icon" src="' . $value->varCountry_flag . '" alt="' . $value->varCountry_name . '" title="' . $value->varCountry_name . '">',
            // $value->varCountry_name,
            $value->varIpAddress,
            $category,
            $details,
//            $icon,
            $date,
            '<a class = "delete" data-bs-toggle="tooltip" data-bs-placement="bottom" title = "Click to Un-block" data-controller="BlockedIpsController" data-alias = "' . $value->id . '"><i class="ri-lock-2-line"></i></a>'
        );
        return $records;
    }

}
