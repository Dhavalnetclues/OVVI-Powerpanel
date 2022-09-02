<?php

namespace Powerpanel\ShieldCMSTheme\Controllers\Powerpanel\log;

use Request;
use App\Log;
use App\Role;
use App\Helpers\resize_image;
use App\User;
use App\Http\Controllers\PowerpanelController;
use App\Helpers\MyLibrary;
use Config;
use Excel;
use App\Modules;
use App\Exports\LogExport;

class LogController extends PowerpanelController {

    /**
     * Create a new controller instance.
     * @return void
     */
    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    /**
     * This method handels load log grid
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function index() {
        $total = Log::getRecords('')->deleted()->count();
        $userslist = User::getUserListForLogFilter();
        $module = Modules::getLogModuleList();
        $this->breadcrumb['title'] = trans('shiledcmstheme::template.logManagerModule.manage');
        return view('shiledcmstheme::powerpanel.logmanager.log_manager', ['iTotalRecords' => $total, 'breadcrumb' => $this->breadcrumb, 'modules' => $module, 'userslist' => $userslist]);
    }

    /**
     * This method handels list of log with filters
     * @return  View
     * @since   2017-07-20
     * @author  NetQuick
     */
    public function get_list() {
        $filterArr = [];
        $records = array();
        $records["data"] = array();
        $filterArr['orderColumnNo'] = (!empty(Request::get('order') [0]['column']) ? Request::get('order') [0]['column'] : '');
        $filterArr['orderByFieldName'] = (!empty(Request::get('columns') [$filterArr['orderColumnNo']]['name']) ? Request::get('columns') [$filterArr['orderColumnNo']]['name'] : '');
        $filterArr['orderTypeAscOrDesc'] = (!empty(Request::get('order') [0]['dir']) ? Request::get('order') [0]['dir'] : '');
        $filterArr['moduleFilter'] = !empty(Request::get('customActionName')) ? Request::get('customActionName') : '';
        $filterArr['pageFilter'] = !empty(Request::get('customPageName')) ? Request::get('customPageName') : '';
        $filterArr['userFilter'] = !empty(Request::get('customFilterUserId')) ? Request::get('customFilterUserId') : '';

        $filterArr['searchFilter'] = !empty(Request::get('searchValue')) ? Request::get('searchValue') : '';
        $filterArr['iDisplayLength'] = intval(Request::get('length'));
        $filterArr['iDisplayStart'] = intval(Request::get('start'));

        $filterArr['customFilterIdentity'] = !empty(Request::get('customFilterIdentity')) ? Request::get('customFilterIdentity') : '';
        if (!empty($filterArr['customFilterIdentity'])) {
            if ($filterArr['customFilterIdentity'] == 'add') {
                $filterArr['customFilterIdentity'] = array("add", "Add", Config::get('Constant.ADDED_DRAFT'));
            } else if ($filterArr['customFilterIdentity'] == 'edit') {
                $filterArr['customFilterIdentity'] = array("edit", Config::get('Constant.UPDATE_DRAFT'));
            } else if ($filterArr['customFilterIdentity'] == 'delete') {
                $filterArr['customFilterIdentity'] = array("delete", Config::get('Constant.DELETE_TRASH_RECORD'), Config::get('Constant.DELETE_DRAFT_RECORD'), Config::get('Constant.DELETE_RECORD'));
            } else if ($filterArr['customFilterIdentity'] == 'trash') {
                $filterArr['customFilterIdentity'] = array("trash", Config::get('Constant.PRIMARY_MOVE_TO_TRASH'));
            } else if ($filterArr['customFilterIdentity'] == 'comment') {
                $filterArr['customFilterIdentity'] = array("comment", Config::get('Constant.COMMENT_ADDED'));
            } else if ($filterArr['customFilterIdentity'] == 'approved') {
                $filterArr['customFilterIdentity'] = array("approved", Config::get('Constant.RECORD_APPROVED'), Config::get('Constant.DRAFT_SENT_FOR_APPROVAL'));
            } else if ($filterArr['customFilterIdentity'] == 'copy') {
                $filterArr['customFilterIdentity'] = array("copy", Config::get('Constant.PRIMARY_RECORD_COPY'));
            } else {
                $filterArr['customFilterIdentity'] = array($filterArr['customFilterIdentity']);
            }
        }

        $sEcho = intval(Request::get('draw'));
        if (null !== Request::get('rid') && Request::get('rid') != '' && null !== Request::get('mid') && Request::get('mid') != '') {
            $rid = Request::get('rid');
            $mid = Request::get('mid');
            $userid = auth()->user()->id;
            if ($userid == 1) {
                $arrResults = Log::getRecords()->deleted()->filter($filterArr)->where('intRecordId', $rid)->where('fkIntModuleId', $mid)->get();
                $iTotalRecords = Log::getRecords()->deleted()->filter($filterArr, true)->where('intRecordId', $rid)->where('fkIntModuleId', $mid)->count();
            } else if ($userid == 2) {
                $arrResults = Log::getRecords()->deleted()->filter($filterArr)->where('intRecordId', $rid)->where('fkIntUserId', '!=', '1')->where('fkIntModuleId', $mid)->get();
                $iTotalRecords = Log::getRecords()->deleted()->filter($filterArr, true)->where('intRecordId', $rid)->where('fkIntUserId', '!=', '1')->where('fkIntModuleId', $mid)->count();
            } else {
                $arrResults = Log::getRecords()->deleted()->filter($filterArr)->where('intRecordId', $rid)->where('fkIntUserId', $userid)->where('fkIntModuleId', $mid)->get();
                $iTotalRecords = Log::getRecords()->deleted()->filter($filterArr, true)->where('intRecordId', $rid)->where('fkIntUserId', $userid)->where('fkIntModuleId', $mid)->count();
            }
        } else {
            $rid = '';
            $userid = auth()->user()->id;
            if ($userid == 1) {
                $arrResults = Log::getRecords()->deleted()->filter($filterArr)->get();
                $iTotalRecords = Log::getRecords()->deleted()->filter($filterArr, true)->count();
            } else if ($userid == 2) {
                $arrResults = Log::getRecords()->deleted()->filter($filterArr)->where('fkIntUserId', '!=', '1')->get();
                $iTotalRecords = Log::getRecords()->deleted()->filter($filterArr, true)->where('fkIntUserId', '!=', '1')->count();
            } else {
                $arrResults = Log::getRecords()->deleted()->filter($filterArr)->where('fkIntUserId', $userid)->get();
                $iTotalRecords = Log::getRecords()->deleted()->filter($filterArr, true)->where('fkIntUserId', $userid)->count();
            }
        }


        $end = $filterArr['iDisplayStart'] + $filterArr['iDisplayLength'];
        $end = $end > $iTotalRecords ? $iTotalRecords : $end;

        if (count($arrResults) > 0 && !empty($arrResults)) {
            foreach ($arrResults as $key => $value) {
                if (isset($value->user)) {
                    $records["data"][] = $this->tableData($value);
                }
            }
        }
        $records["customActionStatus"] = "OK";
        $records["draw"] = $sEcho;
        $records["recordsTotal"] = $iTotalRecords;
        $records["recordsFiltered"] = $iTotalRecords;
        echo json_encode($records);
        exit;
    }

    /**
     * This method destroys Log in multiples
     * @return  Log index view
     * @since   2016-10-25
     * @author  NetQuick
     */
    public function DeleteRecord() {
        $data = Request::get('ids');
        $update = Log::deleteRecordsPermanent($data);
        exit;
    }

    public function tableData($value) {

        $old_val = '';
        $new_val = '';
        $link = '';
        if (strlen($value->txtOldVal) > 0 && (strtolower($value->varAction) == 'edit' || $value->varAction == Config::get('Constant.UPDATE_DRAFT'))) {
            $old_val .= '<a style="display: inline-block;vertical-align: sub;margin-left: 15px;" href="javascript:void(0)" class="without_bg_icon " onclick="return hs.htmlExpand(this,{width:1200,headingText:\'Old Value\',wrapperClassName:\'titlebar\',showCredits:false});"><span aria-hidden="true" class="icon-envelope"></span></a>';
            $old_val .= '<div class="highslide-maincontent">' . $value->txtOldVal . '<br/><div class="highslide-header"><ul style="text-align:center;width: 96%;padding-top:7px"><li class="highslide-close highslide-heading" >New Value</li></ul></div>' . $value->txtNewVal . '</div>';
        } else {
            $old_val .= '-';
        }
//				if (strlen($value->txtNewVal) > 0 && (strtolower($value->varAction) == 'edit' || $value->varAction == Config::get('Constant.UPDATE_DRAFT'))) {
//						$new_val .= '';
//						
//				} else {
//						$new_val .= '-';
//				}
        if ($value->module->varTitle == 'contact') {
            $link .= '<a  href="' . url('powerpanel/' . 'contacts') . '">' . ucfirst($value->module->varTitle) . '</a>';
        } else {
            $link .= '<a href="' . url('powerpanel/' . $value->module->varModuleName) . '">' . ucfirst($value->module->varTitle) . '</a>';
        }
        $roledata = Role::GetRoleTitle($value->fkIntUserId);
        $imagedata = User::GetUserImage($value->fkIntUserId);
        if (!empty($imagedata)) {
            $logo_url = resize_image::resize($imagedata);
            $logo_url = '<img class="img-circle" width="50px" src="' . $logo_url . '"/>';
        } else {
            $logo_url = '<img class="img-circle" width="50px" src="' . Config::get('Constant.CDN_PATH') . 'assets/images/man.png' . '"/>';
        }
        $checkbox = view('powerpanel.partials.checkbox', ['name'=>'delete', 'value'=>$value->id])->render();

        $pubbtn = $value->varAction;
        $pbtn = '';
        if ($pubbtn == 'add' || $pubbtn == 'Add' || $pubbtn == Config::get('Constant.ADDED_DRAFT')) {
            $pbtn = '<div class="pub_status adddiv" data-bs-toggle="tooltip" title="Add"></div>';
        } else if ($pubbtn == 'edit' || $pubbtn == Config::get('Constant.UPDATE_DRAFT')) {
            $pbtn = '<div class="pub_status updatediv" data-bs-toggle="tooltip" title="Update"></div>';
        } else if ($pubbtn == Config::get('Constant.DELETE_TRASH_RECORD') || $pubbtn == Config::get('Constant.DELETE_DRAFT_RECORD') || $pubbtn == Config::get('Constant.DELETE_RECORD') || $pubbtn == 'delete') {
            $pbtn = '<div class="pub_status deletediv" data-bs-toggle="tooltip" title="Delete"></div>';
        } else if ($pubbtn == Config::get('Constant.PRIMARY_MOVE_TO_TRASH') || $pubbtn == Config::get('Constant.PRIMARY_MOVE_TO_TRASH')) {
            $pbtn = '<div class="pub_status transhdiv" data-bs-toggle="tooltip" title="Trash"></div>';
        } else if ($pubbtn == Config::get('Constant.COMMENT_ADDED')) {
            $pbtn = '<div class="pub_status commentdiv" data-bs-toggle="tooltip" title="Comment"></div>';
        } else if ($pubbtn == Config::get('Constant.RECORD_APPROVED') || $pubbtn == Config::get('Constant.SENT_FOR_APPROVAL') || $pubbtn == Config::get('Constant.DRAFT_SENT_FOR_APPROVAL')) {
            $pbtn = '<div class="pub_status approveddiv" data-bs-toggle="tooltip" title="Approved"></div>';
        } else if ($pubbtn == Config::get('Constant.PRIMARY_RECORD_COPY')) {
            $pbtn = '<div class="pub_status copydiv" data-bs-toggle="tooltip" title="Copy"></div>';
        } else {
            $pbtn = '<div class="pub_status otherdiv" data-bs-toggle="tooltip" title="Other"></div>';
        }
        $Title = '<div class="log_super_box">' . $logo_url . '<h5>' . $value->user->name . '</h5><small>' . $roledata . '</small></div>';
        if (ucfirst($value->varAction) == 'Edit') {
            $action = ucfirst($value->varAction) . $old_val;
        } else {
            $action = ucfirst($value->varAction);
        }
        if ($value->varTitle != '') {
            $pagetitle = $value->varTitle . ' (' . $link . ')';
        } else {
            $pagetitle = '-';
        }

        $date = '<span align="left" data-bs-toggle="tooltip" data-bs-placement="bottom" title="'.date(Config::get("Constant.DEFAULT_DATE_FORMAT").' '.Config::get("Constant.DEFAULT_TIME_FORMAT"), strtotime($value->created_at)).'">'.date(Config::get('Constant.DEFAULT_DATE_FORMAT'), strtotime($value->created_at)).'</span>';

        $records = array(
            $checkbox,
            $pbtn,
            $Title,
            $pagetitle,
            $action,
            $value->varIpAddress,
            $date
        );
        return $records;
    }

    /**
     * This method handels export process of Logs
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function ExportRecord() {
        /* $postArr = Request::get();
          if (Request::get('export_type') == 'selected_records') {
          $selectedIds = '';
          if (null !== Request::get('delete')) {
          $selectedIds = Request::get('delete');
          }
          $arrResults = Log::getListForExport($selectedIds);
          } else {
          if (isset($postArr['rid']) && isset($postArr['mid'])) {
          $arrResults = Log::getListForExport(false, $postArr['mid'], $postArr['rid']);
          } else {
          $arrResults = Log::getListForExport();
          }
          }

          if (count($arrResults) > 0) {
          foreach ($arrResults as $key => $value) {
          $roledata = Role::GetRoleTitle($value->fkIntUserId);
          $userName = $value->user->name;
          $useremail = Mylibrary::getDecryptedString($value->user->email);
          $action = $value->varAction;
          $moduleName = $value->module->varTitle;

          $data[] = [
          $userName,
          $roledata,
          $useremail,
          $action,
          $moduleName,
          ($value->varTitle != null) ? $value->varTitle : '-',
          $value->varIpAddress,
          date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . ' ' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', strtotime($value->created_at))
          ];
          }
          $this->createContactLeadExcel($data);
          } */

        return Excel::download(new LogExport, Config::get('Constant.SITE_NAME') . '-' . 'logdata' . '-' . date("dmy-h:i") . '.xlsx');
    }

    /**
     * This method create contact lead excel sheet
     * @return  xls file
     * @since   2016-10-18
     * @author  NetQuick
     */
    public function createContactLeadExcel($data) {

        Excel::create(Config::get('Constant.SITE_NAME') . '-' . 'logdata' . '-' . date("dmy-h:i"), function($excel) use($data) {
            $excel->sheet(date('M-d-Y'), function($sheet) use($data) {
                $sheet->setAutoSize(true);
                $sheet->fromArray($data);
                $sheet->row(1, array(
                    trans('shiledcmstheme::template.common.name'),
                    'User Role',
                    trans('shiledcmstheme::template.common.email'),
                    'Action',
                    'ModuleName',
                    'Record Title',
                    'IP Address',
                    'RECEIVED DATE/TIME'
                ));

                $sheet->prependRow(array(
                    Config::get('Constant.SITE_NAME') . ' Log Details'
                ));

                $sheet->mergeCells('A1:F1');
                $sheet->row(1, function($row) {
                    $row->setAlignment('center');
                    $row->setFontWeight('bold');
                    $row->setFontSize(12);
                });
                $sheet->row(2, function($row) {
                    $row->setAlignment('center');
                    $row->setFontWeight('bold');
                    $row->setFontSize(12);
                });
            });
        })->download('xls');
    }

//    public static function selectRecords() {
//        $data = Request::all();
//        $module = (isset($data['module'])) ? $data['module'] : '';
//
//        $selected = (isset($data['selected']) && $data['selected'] != "") ? $data['selected'] : '';
//
//        $recordSelect = '<option value=" ">--' . trans('shiledcmstheme::template.bannerModule.selectPage') . '--</option>';
//        if ($module != "") {
//
//            $model = Config::get('Constant.MODULE.CONTROLLER_NAME_SPACE') . $data['model'];
//            $module = Modules::getModule($module);
//            $moduleRec = $model::getRecordList(false, false, false);
//            foreach ($moduleRec as $record) {
//                if ($data['id'] == 16) {
//                    $title = $record->display_name;
//                } else if ($data['id'] == 93) {
//                    $title = $record->varIpAddress;
//                } else {
//                    $title = $record->varTitle;
//                }
//                $recordSelect .= '<option data-moduleid="' . $module->id . '" value="' . $record->id . '" ' . ($record->id == $selected ? 'selected' : '') . '>' . ucwords($title) . '</option>';
//            }
//        }
//        return $recordSelect;
//    }

     public static function selectRecords()
    {
        $data = Request::input();
        $module = (isset($data['module'])) ? $data['module'] : '';
        $selected = (isset($data['selected']) && $data['selected'] != "") ? $data['selected'] : '';
        $recordSelect = '<option value=" ">--' . trans('quick-links::template.quickLinkModule.selectPage') . '--</option>';
        if ($module != "") {
            $module = Modules::getModule($module);
            if ($module['varModuleNameSpace'] != '') {
                $model = $module['varModuleNameSpace'] . 'Models\\' . $data['model'];
            } else {
                $model = '\\App\\' . $data['model'];
            }

            if (\Schema::hasColumn($module->varTableName, 'intDisplayOrder')) {
                $filterArray = [];
                $filterArray['orderByFieldName'] = 'intDisplayOrder';
                $filterArray['orderTypeAscOrDesc'] = 'asc';
                $moduleRec = $model::getRecordList($filterArray);
            } else {
                if (isset($module->id) && $module->id != 3) {

                    $moduleRec = $model::getRecordList(false, false, false);
                } else {
                    $moduleRec = $model::getRecordListforinternaldropdown(false, false, false);
                }
            }
            $parentcategorymodles = array();
            if (in_array($data['module'], $parentcategorymodles)) {
                $recordSelect .= ParentRecordHierarchy_builder::Hierarchy_OnlyOptionsForQlinks($moduleRec, $module, $selected);
            } else {
                foreach ($moduleRec as $record) {
                    $sector = '';
                    if ($record->varSector != 'ofreg' && !empty($record->varSector)) {
                        $sector = $record->varSector;
                    }
                    if (strtolower($record->varTitle) != 'home' &&  $record->chrPublish == 'Y') {
                        $recordSelect .= '<option data-moduleid="' . $module->id . '" value="' . $record->id . '" ' . ($record->id == $selected ? 'selected' : '') . '>' . ucwords($record->varTitle) . ($module->id == '3' && !empty($sector) ? ' (' . strtoupper($sector) . ')' : '') . '</option>';
                    }
                }
            }
        }
        return $recordSelect;
    }

}
