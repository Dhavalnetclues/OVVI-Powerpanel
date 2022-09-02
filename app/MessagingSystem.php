<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Cache;
use DB;
use Config;

class MessagingSystem extends Model {

    protected $table = 'messagingsystem';
    protected $fillable = [
        'id',
        'fkMainRecord',
        'chrMain',
        'FromID',
        'FromEmail',
        'FromName',
        'ToName',
        'ToID',
        'ToEmail',
        'chrAddStar',
        'intDisplayOrder',
        'varShortDescription',
        'fkIntDocId',
        'fkIntImgId',
        'chrPublish',
        'chrDelete',
        'chrApproved',
        'intApprovedBy',
        'chrRollBack',
        'UserID',
        'chrAddStar',
        'dtDateTime',
        'dtEndDateTime',
        'created_at'
    ];

    public static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    public function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    public function scopeCheckMainRecord($query, $checkMain = 'Y') {
        $response = false;
        $response = $query->where('chrMain', "=", $checkMain);
        return $response;
    }

    public function scopeCheckStarRecord($query, $flag = 'Y') {
        $response = false;
        $response = $query->where('chrAddStar', "=", $flag);
        return $response;
    }

    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if ($filterArr['orderByFieldName'] != null && $filterArr['orderTypeAscOrDesc'] != null) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('varTitle', 'ASC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }
        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('FromName', 'like', "%" . $filterArr['searchFilter'] . "%")->orWhere('ToName', 'like', "%" . $filterArr['searchFilter'] . "%");
        }
        if (!empty($query)) {
            $response = $query;
        }
        return $response;
    }

    public static function getRecordCount($filterArr = false, $returnCounter = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $pageQuery = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $pageQuery = $pageQuery->filter($filterArr, $returnCounter);
        }
        $response = $pageQuery->deleted()->where('chrMain', 'Y')->count();
        return $response;
    }

    public static function getUserList() {
        DB::enableQueryLog();
        $response = false;
        $response = DB::select('SELECT u.* FROM `nq_users` as u left join nq_messagingsystem as m on 
                            (m.FromID=u.id and m.ToID=' . auth()->user()->id . '  )
                            or (m.FromID=' . auth()->user()->id . ' and m.ToID=u.id ) 
                      WHERE m.created_at in (SELECT MAX(m.created_at) from nq_messagingsystem as m 
                      where (m.FromID=u.id and m.ToID=' . auth()->user()->id . '  ) 
                      or (m.FromID=' . auth()->user()->id . ' and m.ToID=u.id ) ) 
                      or  m.FromName IS NULL and u.chrDelete="N"  and u.chrPublish="Y" GROUP BY u.id ORDER BY m.created_at desc');
        return $response;
    }

    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false) {
        $response = 0;
        $cmsPageFields = ['id'];
        $userid = auth()->user()->id;
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        if ($isAdmin) {
            $response = $response->checkStarRecord('N');
        }
        $response = $response->deleted()
                ->where('chrMain', 'Y')
                ->where("FromID", '=', $userid)
                ->orWhere("ToID", '=', $userid)
                ->orderBy('created_at', '=', 'asc')
                ->count();
        return $response;
    }
 public static function getRecordById($id, $moduleFields = false) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }
    public static function getNewRecordsCount() {
        $NewRecordsCount = Self::select('*')->where('chrAddStar', 'Y')->where('chrDelete', 'N')->count();
        return $NewRecordsCount;
    }

    public static function getRecordCount_letest($Main_id, $id) {
        $moduleFields = ['chrLetest'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('chrMain', 'N')
                ->where('fkMainRecord', $Main_id)
                ->where('chrLetest', 'Y')
                ->where('id', '!=', $id)
                ->where('chrApproved', 'N')
                ->count();
        return $response;
    }

    public static function getChildGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'FromID', 'FromEmail', 'FromName', 'ToName', 'ToID', 'ToEmail', 'varShortDescription', 'fkIntImgId', 'fkIntDocId', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID', 'created_at'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }

    public static function getChildrollbackGrid() {
        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varTitle', 'FromID', 'FromEmail', 'FromName', 'ToName', 'ToID', 'ToEmail', 'varShortDescription', 'fkIntImgId', 'fkIntDocId', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID', 'created_at'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('chrRollBack', 'Y')->where('fkMainRecord', $id)->orderBy('created_at', 'desc')->get();
        return $response;
    }

    public static function approved_data_Listing($request) {
        $id = $request->post('id');
        $main_id = $request->post('main_id');
        $PUserid = $request->post('PUserid');
        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
            'varShortDescription',
            'fkIntDocId',
            'fkIntImgId',
            'FromID',
            'FromEmail',
            'FromName',
            'ToName',
            'ToID',
            'ToEmail',
            'intDisplayOrder',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
            'created_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('chrMain', 'N')->where('id', $id)->orderBy('created_at', 'desc')->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varTitle' => $response['varTitle'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'chrAddStar' => 'N',
            'chrPublish' => $response['chrPublish'],
        ];
        CommonModel::updateRecords($whereConditions, $updateMainRecord);
        //Update Copy Child Record To Main Record end
        $whereConditions_ApproveN = ['fkMainRecord' => $main_id];
        $updateToApproveN = [
            'chrApproved' => 'N',
            'chrLetest' => 'N',
            'intApprovedBy' => '0',
        ];
        CommonModel::updateRecords($whereConditions_ApproveN, $updateToApproveN);
        $whereConditionsApprove = ['id' => $id, 'chrMain' => 'N'];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove);
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public static function getRecordforEmailById($id) {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
        ];
        $response = Cache::tags(['messagingsystem'])->get('getRecordforEmailById_' . $id);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->orderBy('intDisplayOrder')
                    ->where('chrMain', 'Y')
                    ->CheckRecordId($id)
                    ->first();
            Cache::tags(['messagingsystem'])->forever('getRecordforEmailById_' . $id, $response);
        }
        return $response;
    }

    public static function getFrontList() {
        $response = false;
        $moduleFields = ['id',
            'varTitle',
        ];
        $response = Cache::tags(['messagingsystem'])->get('messagingsystemFrontList');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->orderBy('intDisplayOrder')
                    ->where('chrMain', 'Y')
                    ->get();
            Cache::tags(['messagingsystem'])->forever('messagingsystemFrontList', $response);
        }
        return $response;
    }

    public static function getFrontListForFooter() {
        $response = false;
        $moduleFields = [
            'id',
            'varTitle',
        ];
        $response = Cache::tags(['messagingsystem'])->get('messagingsystemFrontListForFooter');
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->dateRange()
                    ->orderBy('intDisplayOrder')
                    ->where('chrMain', 'Y')
                    ->get();
            Cache::tags(['messagingsystem'])->forever('messagingsystemFrontListForFooter', $response);
        }
        return $response;
    }

    public static function getFrontRecords($moduleFields = false, $aliasFields = false) {
        $data = [];
        if ($aliasFields != false) {
            $data = [
                'alias' => function ($query) use ($aliasFields) {
                    $query->select($aliasFields);
                },
            ];
        }
        return self::select($moduleFields)->with($data);
    }

    public function scopeDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

    public static function GetMessageidData($toid, $fromid) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where(function ($query) use ($toid, $fromid) {
                    $query->where("FromID", '=', $fromid)->where('ToID', '=', $toid)
                    ->orWhere('FromID', '=', $toid)->where("ToID", '=', $fromid);
                })
//                ->orderBy('created_at', '=', 'asc')
                ->orderBy('created_at', 'asc')
                ->get();
        return $response;
    }

    public static function GetCountNewMessageidData($toid, $fromid) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where(function ($query) use ($toid, $fromid) {
                    $query->where("FromID", '=', $toid)->where('ToID', '=', $fromid)->where('varread', 'N');
                })
                ->orderBy('created_at', 'asc')
                ->count();
        return $response;
    }

    public static function GetRecentid($fromid) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where("FromID", '=', $fromid)
                ->orderBy('created_at', 'desc')
                ->first();
        return $response;
    }

    public static function GetlastDate($toid, $fromid) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where(function ($query) use ($toid, $fromid) {
                    $query->where("FromID", '=', $fromid)->where('ToID', '=', $toid)
                    ->orWhere('FromID', '=', $toid)->where("ToID", '=', $fromid);
                })
                ->orderBy('id', 'desc')
                ->first();
        return $response;
    }

    public static function GetNewMessageidData($toid, $fromid) {
        $response = false;
        $moduleFields = ['*'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where(function ($query) use ($toid, $fromid) {
                    $query->where("FromID", '=', $toid)->where('ToID', '=', $fromid)->where('varread', 'N');
                })
                ->orderBy('created_at', 'asc')
                ->get();
        return $response;
    }

    public static function relative_date($time) {
        $today = strtotime(date('M j, Y'));

        $reldays = ($time - $today) / 86400;

        if ($reldays >= 0 && $reldays < 1) {

            return date('' . Config::get('Constant.DEFAULT_TIME_FORMAT') . '', $time);
        } else if ($reldays >= 1 && $reldays < 2) {

            return 'Tomorrow';
        } else if ($reldays >= -1 && $reldays < 0) {

            return 'Yesterday';
        }

        if (abs($reldays) < 7) {

            if ($reldays > 0) {
                $reldays = floor($reldays);

                return 'In ' . $reldays . ' day' . ($reldays != 1 ? 's' : '');
            } else {

                $reldays = abs(floor($reldays));
                $dayname = date('D', $time);

                return $dayname.' '.$reldays . ' day' . ($reldays != 1 ? 's' : '') . ' ago';
            }
        }

        if (abs($reldays) < 182) {

//            return date('l, j F', $time ? $time : time());
            return date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . '', $time);
        } else {

//            return date('l, j F, Y', $time ? $time : time());
            return date('' . Config::get('Constant.DEFAULT_DATE_FORMAT') . '', $time);
        }
    }

}
