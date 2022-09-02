<?php

namespace Powerpanel\HitsReport\Models;

use Illuminate\Database\Eloquent\Model;
use DB;

class HitsReport extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'page_hits';

    public static function getHitsWebHitsyears($year = false, $month = false, $isWeb = false) {
        $response = Self::select('id');
        $response = $response->where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N')->where('isWeb', '=', $isWeb);
        $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "");
        $response = $response->whereRaw("MONTH(created_at) = " . (int) $month . "")->count();
        return $response;
    }

    public static function getSumWebHitsyears($year = false, $isWeb = false) {
        $response = Self::select('id');
        $response = $response->where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N')->where('isWeb', '=', $isWeb);
        $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->count();
        return $response;
    }

    public static function getHitsreport($year = false, $isWeb = false) {
        $response = Self::select(DB::raw("COUNT(id) as hits"), DB::raw("MONTH(created_at) AS month"));
        $response = $response->where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N');
        if($year != '') {
            $response = $response->whereRaw(DB::raw("YEAR(created_at)") . " = " . (int) $year . "");
        }
        if($isWeb != '') {
            $response = $response->where('isWeb', '=', $isWeb);
        }
        $response = $response->groupBy(DB::raw("MONTH(created_at)"))->get();
        return $response;
    }

}
