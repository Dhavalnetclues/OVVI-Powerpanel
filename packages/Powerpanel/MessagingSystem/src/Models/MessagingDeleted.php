<?php

namespace Powerpanel\MessagingSystem\Models;

use Illuminate\Database\Eloquent\Model;
use Cache;
use Carbon\Carbon;
use DB;
use Config;
use App\CommonModel;

class MessagingDeleted extends Model {

    protected $table = 'messagingsystemdeleted';
    protected $fillable = ['*'];

    public static function DeletedRecordMsg($toid, $fromid) {
        $deletd = new MessagingDeleted;

        $messagingsystemdArr['FromID'] = $fromid;
        $messagingsystemdArr['ToID'] = $toid;
        $messagingsystemdArr['created_at'] = Carbon::now();
        $messagingsystemdArr['chrPublish'] = 'Y';

        $messagingsystemID = CommonModel::addRecord($messagingsystemdArr, $deletd);

        self::flushCache();

        return $messagingsystemID;
    }

    public static function flushCache() {
        Cache::tags('messagingsystemdeleted')->flush();
    }

    public static function GetCountDeteled($toid, $fromid) {
        $response = false;
        $moduleFields = ['*'];
        $response = MessagingDeleted::getPowerPanelRecords($moduleFields)->where(function ($query) use ($toid, $fromid) {
                    $query->where("FromID", '=', $toid)->where('ToID', '=', $fromid);
                })
                ->count();
        return $response;
    }

    public static function getPowerPanelRecords($moduleFields = false) {
        $data = [];
        $response = false;
        $response = self::select($moduleFields);
        if (count($data) > 0) {
            $response = $response->with($data);
        }
        return $response;
    }

}
