<?php

namespace App;

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

}
