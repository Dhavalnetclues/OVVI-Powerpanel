<?php

namespace App;

use App\Notifications\MailResetPasswordToken;
use DB;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasRoles;
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id', 'name', 'intAttempts', 'email', 'personalId', 'fkIntImgId', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * This method handels retrival of record count based on category
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getCountById($categoryId = null)
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->checkCategoryId($categoryId)
            ->deleted()
            ->count();
        return $response;
    }

    public static function addChartData($userid, $data)
    {
        DB::table('dashboardorder')->where('UserID', $userid)->delete();
        DB::table('dashboardorder')->insertGetId($data);
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordByIdIn($arrId = false)
    {
        $response = false;
        $moduleFields = ['id', 'name'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->checkRecordIdIn($arrId)
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordListIndex()
    {
        $response = false;
        $moduleFields = ['id', 'name', 'intAttempts', 'email'];
        $roleUserFields = ['user_id', 'role_id'];
        $roleFields = ['id', 'display_name','varSector'];
        $response = Self::getPowerPanelRecords($moduleFields, $roleUserFields, $roleFields)
            ->deleted()
            ->orderById('DESC')
            ->paginate(5);
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordList($filterArr = false)
    {
        $response = false;
        $moduleFields = ['users.id',
            'users.name',
            'users.intAttempts',
            'users.email',
            'users.chrPublish',
            'users.chrAuthentication',
        ];

        $response = Self::select($moduleFields)
            ->with('roles')
            ->deleted()
            ->filter($filterArr)
            ->orderBy('users.name')
            ->get();

        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getUserListForLogFilter($filterArr = false)
    {
        $response = false;
        $loggedUserid = auth()->user()->id;
        $moduleFields = ['id', 'name', 'intAttempts', 'email', 'chrPublish', 'chrAuthentication'];
        $roleUserFields = ['user_id', 'role_id'];
        $roleFields = ['id', 'name', 'display_name', 'varSector', 'chrIsAdmin'];
        $response = Self::getPowerPanelRecords($moduleFields, $roleUserFields, $roleFields)
            ->deleted();
        if ($loggedUserid != 1) {
            $response = $response->where('id', "!=", 1);
        }
        $response = $response->orderBy('name')
            ->get();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordById($id = false, $deleteScope = false)
    {
        $response = false;
        $moduleFields = ['users.id',
            'users.name',
            'users.intAttempts',
            'users.email',
            'users.personalId',
            'users.password',
            'users.pass_change_dt',
            'users.chrAuthentication',
            'users.Int_Authentication_Otp',
            'users.fkIntImgId',
            'users.chrSecurityQuestions',
            'users.SecurityQuestions_start_date',
            'users.intSearchRank',
            'users.varQuestion1',
            'users.varQuestion2',
            'users.varQuestion3',
            'users.varAnswer1',
            'users.varAnswer2',
            'users.varAnswer3',
            'users.chrPublish',
            'users.chrAuthentication',
        ];

        $response = Self::with('roles');
        if ($deleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)->first();
        return $response;
    }

    public static function getUserId($id = false, $deleteScope = false)
    {
        $response = false;
        $moduleFields = ['id', 'name', 'intAttempts', 'email', 'password', 'chrPublish', 'fkIntImgId'];
        $roleUserFields = ['user_id', 'role_id'];
        $roleFields = ['id', 'name', 'display_name', 'varSector'];
        $response = Self::getPowerPanelRecords($moduleFields, $roleUserFields, $roleFields);
        if ($deleteScope) {
            $response = $response->deleted();
        }
        $response = $response->checkRecordId($id)->first();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordByIdWithoutRole($id = false)
    {
        $response = false;
        $moduleFields = ['id', 'name', 'intAttempts', 'email', 'fkIntImgId', 'password', 'chrPublish'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->checkRecordId($id)
            ->first();
        return $response;
    }

    /**
     * This method handels retrival of record count
     * @return  Object
     * @since   2017-10-16
     * @author  NetQuick
     */
    public static function getRecordByEmailID($emailID = false)
    {
        $response = false;
        $moduleFields = ['id', 'name', 'intAttempts', 'email', 'personalId', 'fkIntImgId', 'password', 'chrPublish', 'chrAuthentication'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->deleted()
            ->CheckByEmail($emailID)
            ->first();

        return $response;
    }

    public static function deleteRecordsPermanent($data = false)
    {

        Self::whereIn('id', $data)->update([
            'chrPublish' => 'N',
            'chrDelete' => 'Y',
        ]);

        //  DB::statement('SET FOREIGN_KEY_CHECKS=0');
        // self::whereIn('id',$data)->delete();
        // DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }

    #Database Configurations========================================
    /**
     * This method handels retrival of blog records
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */

    public static function getPowerPanelRecords($moduleFields = false, $roleUsesrFields = false, $roleFields = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);

        if ($roleUsesrFields != false && $roleFields != false) {

            // $data['roleUser'] = function ($query) use ($roleUsesrFields) {
            //     $query->select($roleUsesrFields);
            // };
            // $data['roles'] = function ($query) use ($roleFields) {
            //     $query->select($roleFields)->deleted();
            // };
        }

        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    /**
     * This method handels record id scope
     * @return  Object
     * @since   2016-07-24
     * @author  NetQuick
     */
    public function scopeCheckRecordId($query, $id)
    {
        return $query->where('users.id', $id);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopePublish($query)
    {
        return $query->where(['users.chrPublish' => 'Y']);
    }

    /**
     * This method handels publish scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeCheckByEmail($query, $email)
    {
        return $query->where(['users.email' => $email]);
    }

    /**
     * This method handels delete scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeDeleted($query)
    {
        return $query->where(['users.chrDelete' => 'N']);
    }

    /**
     * This method handels orderby desc scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeOrderById($query, $orderType)
    {
        return $query->orderBy('users.id', $orderType);
    }

    public function scopecheckRecordIdIn($query, $arrId)
    {
        return $query->whereIn('users.id', $arrId);
    }

    /**
     * This method handels filter scope
     * @return  Object
     * @since   2016-07-14
     * @author  NetQuick
     */
    public function scopeFilter($query, $filterArr = false, $retunTotalRecords = false)
    {
        $response = null;
        if (!empty($filterArr['orderByFieldName']) && !empty($filterArr['orderTypeAscOrDesc'])) {
            $query = $query->orderBy('users.' . $filterArr['orderByFieldName'], $filterArr['orderTypeAscOrDesc']);
        } else {
            $query = $query->orderBy('users.name', 'ASC')->orderBy('intAttempts', 'ASC');
        }

        if (!$retunTotalRecords) {
            if (!empty($filterArr['iDisplayLength']) && $filterArr['iDisplayLength'] > 0) {
                $data = $query->skip($filterArr['iDisplayStart'])->take($filterArr['iDisplayLength']);
            }
        }
        if (!empty($filterArr['statusFilter']) && $filterArr['statusFilter'] != ' ') {
            $data = $query->where('users.chrPublish', $filterArr['statusFilter']);
        }

        if (!empty($filterArr['searchFilter']) && $filterArr['searchFilter'] != ' ') {

            $data = $query->where('users.name', 'like', '%' . $filterArr['searchFilter'] . '%');
            /* $data = $query->orWhere('email','like', '%' . MyLibrary::getEncryptedString($filterArr['searchFilter']) . '%');             */
        }

        if (!empty($query)) {
            $response = $query;
        }

        return $response;
    }

    public static function updateUserRecordByEmail($email, $data)
    {
        $user = self::where('users.email', '=', $email)->update($data);
        return $user;
    }

    /**
     * Send a password reset email to the user
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new MailResetPasswordToken($token, $this));
    }

    /**
     * This method handels team relation
     * @return  Object
     * @since   2017-08-01
     * @author  NetQuick
     */

    // public function roleUser()
    // {
    //     return $this->belongsTo('App\Role_user', 'id', 'user_id');
    // }

    public function log()
    {
        return $this->hasOne('App\Log', 'id', 'fkIntUserId');
    }

    public function emailLog()
    {
        return $this->hasOne('App\EmailType', 'id', 'fkIntUserId');
    }

    public function loginHistory()
    {
        return $this->hasOne('App\LoginLog', 'id', 'fkIntUserId');
    }

    public static function GetUserImage($id)
    {
        $pagedata = DB::table('users')
            ->select('*')
            ->where('id', '=', $id)
            ->first();
        return $pagedata->fkIntImgId;
    }

    public static function GetUserName($id)
    {
        $pagedata = DB::table('users')
            ->select('*')
            ->where('id', '=', $id)
            ->first();
        return $pagedata->name;
    }

    public static function GetUserEmail($id)
    {
        $pagedata = DB::table('users')
            ->select('*')
            ->where('id', '=', $id)
            ->first();
        return $pagedata->email;
    }

    public static function GetUserData()
    {
        $pagedata = DB::table('users')
            ->select('*')
            ->where('id', '!=', auth()->user()->id)
            ->orderBy('created_at', 'DESC')
            ->get();
        return $pagedata;
    }

    public static function GetSecurityQuestion()
    {
        $pagedata = DB::table('security_questions')
            ->select('*')
            ->orderBy('id', 'ASC')
            ->get();
        return $pagedata;
    }

    public static function GetSecurityQuestion_byId($Question)
    {
        $pagedata = DB::table('security_questions')
            ->select('var_questions')
            ->where('id', $Question)
            ->first();
        return $pagedata->var_questions;
    }

    public static function GetSecurityQuestion_Random($arrId1 = false, $arrId2 = false, $arrId3 = false)
    {
        $randomUser = DB::table('security_questions')
            ->whereIn('id', array($arrId1, $arrId2, $arrId3))
            ->inRandomOrder()
            ->first();
        return $randomUser;
    }

    public static function checkAns($id = false, $QuestionId = false, $SecurityAnswer = false)
    {
        $moduleFields = ['id'];
        $response = false;
        $response = Self::getRecords($moduleFields)
            ->where('id', $id)
            ->where($QuestionId, $SecurityAnswer)
            ->count();
        return $response;
    }

    public static function getRecords($moduleFields = false, $pageFields = false)
    {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if ($pageFields != false) {
            $data['pages'] = function ($query) use ($pageFields) {
                $query->select($pageFields);
            };
        }
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

    public static function getRecordByIdPublicOrDelete($id = false)
    {
        $response = false;
        $moduleFields = ['id'];
        $response = Self::getPowerPanelRecords($moduleFields)
            ->publish()
            ->checkRecordId($id)
            ->first();
        return $response;
    }

}
