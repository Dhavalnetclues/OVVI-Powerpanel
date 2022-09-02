<?php

/**
 * The FormBuilder class handels bannner queries
 * ORM implemetation.
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since       2017-07-20
 * @author    NetQuick
 */

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;
use Cache;

class FormBuilder extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'form_builder';
    protected $fillable = [
        'id',
        'varName',
        'FormTitle',
        'Description',
        'varFormDescription',
        'varEmail',
        'varAdminSubject',
        'varAdminContent',
        'chrCheckUser',
        'varUserSubject',
        'varUserContent',
        'varThankYouMsg',
        'UserID',
        'fkIntImgId',
        'chrPublish',
        'chrDelete',
        'created_at',
        'updated_at'
    ];

    /**
     * This method handels retrival of videogallery records
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public static function getRecords() {
        $response = false;
        $response = Cache::tags(['FormBuilder'])->get('getFormBuilderRecords');
        if (empty($response)) {
            $response = Self::Select(['id', 'varName', 'txtLink', 'fkIntImgId', 'intDisplayOrder', 'chrPublish'])
                    ->deleted()
                    ->publish()
                    ->paginate(10);
            Cache::tags(['FormBuilder'])->forever('getFormBuilderRecords', $response);
        }
        return $response;
    }

    /**
     * This method handels backend records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        if (count($data) > 0) {
            $response = $response->with($data);
        }

        return $response;
    }

    /**
     * This method handels retrival of backend record list
     * @return  Object
     * @since   2017-10-24
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false, $isAdmin = false) {
        $response = false;
        $userid = auth()->user()->id;
        $moduleFields = [
            'id',
            'varName',
            'FormTitle',
            'Description',
            'varFormDescription',
            'varEmail',
            'varAdminSubject',
            'varAdminContent',
            'varThankYouMsg',
            'chrCheckUser',
            'fkIntImgId',
            'varUserSubject',
            'varUserContent',
            'UserID',
            'chrPublish',
            'chrDelete',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted();
        if (!$isAdmin) {
            $response = $response->where("UserID", '=', $userid);
        }
        $response = $response->filter($filterArr)
                ->get();
        return $response;
    }

    /**
     * This method handels retrival of front Product list
     * @return  Object
     * @since   2017-10-14
     * @author  NetQuick
     */
    public static function getFrontList($limit = 10, $page = 1) {
        $response = false;
        $moduleFields = [
            'id',
            'varName',
            'FormTitle',
            'Description',
            'varFormDescription',
            'varEmail',
            'varAdminSubject',
            'varAdminContent',
            'varThankYouMsg',
            'chrCheckUser',
            'fkIntImgId',
            'varUserSubject',
            'varUserContent',
            'UserID',
            'chrPublish',
            'chrDelete',
            'created_at',
            'updated_at'
        ];
        $response = Cache::tags(['FormBuilder'])->get('getFrontFormBuilderList_' . $page);
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields)
                    ->deleted()
                    ->publish()
                    ->orderBy('intDisplayOrder', 'asc')
                    ->paginate($limit);
            Cache::tags(['FormBuilder'])->forever('getFrontFormBuilderList_' . $page, $response);
        }
        return $response;
    }

    public static function getFrontRecords($moduleFields = false, $aliasFields = false) {
        $response = false;
        $data = [];
        $response = self::select($moduleFields)->with($data);
        return $response;
    }

    /**
     * This method handels retrival of record by id
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id, $ignoreDeleteScope = false) {
        $response = false;
        $moduleFields = [
            'id',
            'varName',
            'FormTitle',
            'Description',
            'varFormDescription',
            'varEmail',
            'varAdminSubject',
            'varAdminContent',
            'varThankYouMsg',
            'chrCheckUser',
            'fkIntImgId',
            'varUserSubject',
            'varUserContent',
            'UserID',
            'chrPublish',
            'chrDelete',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields);
        if (!$ignoreDeleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of record by id for Log Manage
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordForLogById($id) {
        $response = false;
        $moduleFields = [
            'id',
            'varName',
            'FormTitle',
            'Description',
            'varFormDescription',
            'varEmail',
            'varAdminSubject',
            'varAdminContent',
            'varThankYouMsg',
            'chrCheckUser',
            'fkIntImgId',
            'varUserSubject',
            'varUserContent',
            'UserID',
            'chrPublish',
            'chrDelete',
            'created_at',
            'updated_at'
        ];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->checkRecordId($id)->first();
        return $response;
    }

    public static function getRecordCount_letest($Main_id, $id) {
        $moduleFields = ['chrLetest'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->where('id', '!=', $id)
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    protected static $fetchedOrder = [];
    protected static $fetchedOrderObj = null;

    public static function getRecordByOrder($order = false) {
        $response = false;
        $moduleFields = [
            'id',
            'intDisplayOrder',
        ];
        if (!in_array($order, Self::$fetchedOrder)) {
            array_push(Self::$fetchedOrder, $order);
            Self::$fetchedOrderObj = Self::getPowerPanelRecords($moduleFields)
                    ->deleted()
                    ->orderCheck($order)
                    ->first();
        }
        $response = Self::$fetchedOrderObj;
        return $response;
    }

    public static function getOrderOfApproval($id) {
        $result = Self::select('intDisplayOrder')
                ->checkRecordId($id)
                ->first();
        return $result;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordId($query, $id) {
        return $query->where('id', $id);
    }

    /**
     * This method handels order scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeOrderCheck($query, $order) {
        return $query->where('intDisplayOrder', $order);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopePublish($query) {
        return $query->where(['chrPublish' => 'Y']);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-20
     * @author  NetQuick
     */
    public function scopeDeleted($query) {
        return $query->where(['chrDelete' => 'N']);
    }

    /**
     * This method handels Main Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckMainRecord($query, $checkMain = 'Y') {
        $response = false;
        $response = $query->where('chrMain', "=", $checkMain);
        return $response;
    }

    /**
     * This method handels Main Record scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckStarRecord($query, $flag = 'Y') {
        $response = false;
        $response = $query->where('chrAddStar', "=", $flag);
        return $response;
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false) {
        $response = null;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $query = $query->orderBy($filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('varName', 'ASC');
        }
        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('chrPublish', $filterArr['statusFilter']);
        }

        if (isset($filterArr['ignore']) && !empty($filterArr['ignore'])) {
            $data = $query->whereNotIn('form_builder.id', $filterArr['ignore']);
        }

        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {
            $data = $query->where('varName', 'like', "%" . $filterArr['searchFilter'] . "%");
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
        $response = $pageQuery->deleted()
                ->count();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2018-10-08
     * @author  NetQuick
     */
    public static function getRecordCountforList($filterArr = false, $returnCounter = false, $isAdmin = false) {
        $response = 0;
        $userid = auth()->user()->id;
        $cmsPageFields = ['id'];
        $response = Self::getPowerPanelRecords($cmsPageFields);
        if ($filterArr != false) {
            $response = $response->filter($filterArr, $returnCounter);
        }
        $response = $response->deleted();
        if (!$isAdmin) {
            $response = $response->where("UserID", '=', $userid);
        }
        $response = $response->count();
        return $response;
    }

    public static function getRecordCountListApprovalTab($filterArr = false) {

        $response = false;
        $MainIDs = Self::distinct()
                ->select("fkMainRecord")
                ->where('fkMainRecord', '!=', '0')
                ->groupBy('fkMainRecord')
                ->get()
                ->toArray();
        $MainIDs = array_column($MainIDs, 'fkMainRecord');

        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
                ->deleted()
                ->filter($filterArr)
                ->whereIn('id', $MainIDs)
                ->where('chrTrash', '!=', 'Y')
                ->count();
        return $response;
    }

    public static function getNewRecordsCount() {
        $NewRecordsCount = Self::select('*')->where('chrDelete', 'N')->count();
        return $NewRecordsCount;
    }

    public static function getChildGrid() {

        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varName', 'txtLink', 'fkIntImgId', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'intSearchRank', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID', 'chrDraft', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];

        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->orderBy('created_at', 'desc')->get();
        return $response;
    }

    public static function getChildrollbackGrid() {

        $id = $_REQUEST['id'];
        $response = false;
        $moduleFields = ['id', 'varName', 'txtLink', 'fkIntImgId', 'intDisplayOrder', 'chrPublish', 'fkMainRecord', 'created_at', 'intSearchRank', 'chrApproved', 'updated_at', 'intApprovedBy', 'UserID', 'chrDraft', 'chrTrash', 'FavoriteID', 'created_at', 'updated_at'];

        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->orderBy('created_at', 'desc')->get();
        return $response;
    }

    public static function approved_data_Listing($request) {
        $id = $request->id;
        $main_id = $request->main_id;
        $PUserid = $request->PUserid;

        //Select Child Record Data Start
        $response = false;
        $moduleFields = [
            'id',
            'varName',
            'intSearchRank',
            'txtLink',
            'fkIntImgId',
            'intDisplayOrder',
            'chrPublish',
            'dtDateTime',
            'dtEndDateTime',
            'chrDraft',
            'chrTrash',
            'intSearchRank',
            'FavoriteID',
            'created_at',
            'updated_at'
        ];
        $aliasFields = ['id', 'varAlias'];
        $response = Self::getPowerPanelRecords($moduleFields)->deleted()->where('id', $id)->orderBy('created_at', 'desc')->first();
        //Select Child Record Data END
        //Update Copy Child Record To Main Record start
        $whereConditions = ['id' => $main_id];
        $updateMainRecord = [
            'varName' => $response['varName'],
            'intSearchRank' => $response['intSearchRank'],
            'dtDateTime' => $response['dtDateTime'],
            'dtEndDateTime' => $response['dtEndDateTime'],
            'txtLink' => $response['txtLink'],
            'fkIntImgId' => $response['fkIntImgId'],
            'chrDraft' => $response['chrDraft'],
            'intSearchRank' => $response['intSearchRank'],
            'FavoriteID' => $response['FavoriteID'],
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

        $whereConditionsApprove = ['id' => $id];
        $updateToApprove = [
            'chrApproved' => 'Y',
            'chrRollBack' => 'Y',
            'intApprovedBy' => auth()->user()->id
        ];
        CommonModel::updateRecords($whereConditionsApprove, $updateToApprove);
        $msg_show = "Record successfully approved.";
        return $msg_show;
    }

    public function scopeDateRange($query) {
        $response = false;
        $response = $query->whereRaw('((dtEndDateTime >= NOW() AND NOW() >= dtDateTime) OR (NOW() >= dtDateTime and dtEndDateTime is null))');
        return $response;
    }

    public static function getBuilderRecordList($filterArr = []) {
        $response = false;
        $moduleFields = [
            'form_builder.id',
            'form_builder.fkIntImgId',
            'form_builder.varName',
            'form_builder.chrPublish',
            'form_builder.chrDelete',
            'form_builder.dtDateTime',
            'form_builder.dtEndDateTime',
            'form_builder.updated_at'
        ];

        $response = Self::getPowerPanelRecords($moduleFields, false, false, false, false)
                ->filter($filterArr);

        $response = $response->where('form_builder.chrPublish', 'Y')
                ->where('form_builder.chrDelete', 'N')
                ->groupBy('form_builder.id')
                ->get();
        return $response;
    }

    public static function getBuilderFormBuilder($fields, $recIds) {
        $response = false;
        $moduleFields = $fields['moduleFields'];
        array_push($moduleFields, 'fkIntImgId');
        $aliasFields = ['id', 'varAlias'];
        $response = Cache::tags(['VideoAlbum'])->get('getBuilderFormBuilder_' . implode('-', $recIds));
        if (empty($response)) {
            $response = Self::getFrontRecords($moduleFields, $aliasFields)
                    ->whereIn('id', $recIds)
                    ->deleted()
                    ->publish()
                    ->orderByRaw(DB::raw("FIELD(id, " . implode(',', $recIds) . " )"))
                    ->get();
            Cache::tags(['VideoAlbum'])->forever('getBuilderFormBuilder_' . implode('-', $recIds), $response);
        }
        return $response;
    }

    public static function insertformdata($request) {

        if (isset($request) && $request['fromdata'] != '[]') {
            $data = array(
                'varName' => $request['formtitle'],
                'FormTitle' => $request['formtitle1'],
                'Description' => $request['formdesc'],
                'varFormDescription' => $request['fromdata'],
                'varEmail' => $request['email'],
                'varAdminSubject' => $request['subject'],
                'varAdminContent' => $request['content'],
                'chrCheckUser' => $request['check_user'],
                'varUserSubject' => $request['user_subject'],
                'varUserContent' => $request['user_content'],
                'UserID' => auth()->user()->id,
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'created_at' => date('Y-m-d H:i'),
                'updated_at' => date('Y-m-d H:i')
            );
            $formdatainsert = DB::table('nq_form_builder')->insert($data);
//            $formdatainsert = DB::insert("insert into nq_form_builder (id,varName,FormTitle,Description,varFormDescription,varEmail,varAdminSubject,varAdminContent,chrCheckUser,varUserSubject,varUserContent,UserID,chrPublish,chrDelete,created_at,updated_at) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)", 
//                    array('', $request['formtitle'], $request['formtitle1'], $request['formdesc'], $request['fromdata'], $request['email'], $request['subject'], $request['content'], $request['check_user'], $request['user_subject'], $request['user_content'], auth()->user()->id, 'Y', 'N', date('Y-m-d H:i'), date('Y-m-d H:i')));
            return $formdatainsert;
        }
    }

    public static function getCountry() {
        $data = DB::table('country')
                ->select('*')
                ->get();
        return $data;
    }

    public static function getState() {
        $data = DB::table('state')
                ->select('*')
                ->get();
        return $data;
    }
    public static function getUsState($id) {
        $data = DB::table('state')
                ->select('*')
                ->where('fk_country','=',$id)
                ->get();
        return $data;
    }

}
