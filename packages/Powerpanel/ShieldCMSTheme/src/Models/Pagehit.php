<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class Pagehit extends Model {

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $table = 'page_hits';
    protected $fillable = [
        'id',
        'fkIntAliasId',
        'varBrowserInfo',
        'isWeb',
        'varIpAddress',
        'varIsoCode',
        'varCountry',
        'varCity',
        'varState',
        'varStateName',
        'varPostalCode',
        'varLatitude',
        'varLongitude',
        'varTimezone',
        'varContinent',
        'varCurrency',
        'created_at',
        'updated_at'
    ];

    public static function getHitsWebMobileHits() {
        $year = [date('Y'), date("Y", strtotime("-1 year")), date("Y", strtotime("-2 year")), date("Y", strtotime("-3 year"))];
        $resultarryyear = array();
        foreach ($year as $value) {
            $Web_hits = DB::select(DB::raw("SELECT distinct (select count(*) from `nq_page_hits` where `isWeb`='Y' and year(created_at) = '$value') as webhits, "
                                    . "(select count(*) from `nq_page_hits` where `isWeb`='N' and year(created_at) = '$value') as mobilehits "
                                    . "FROM `nq_page_hits` where year(created_at)='$value'"));
            if (!empty($Web_hits)) {
                $resultarryyear[$value] = $Web_hits[0];
            }
        }
        return $resultarryyear;
    }

    public static function getHitsWebMobileHitsyears($year = false, $isWeb = false, $timeparam = false, $month = false) {
        $response = Self::select('id');
        $response = $response->where('chrPublish', '=', 'Y')->where('chrDelete', '=', 'N')->where('isWeb', '=', $isWeb);
        if ($timeparam != 'month') {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->count();
        } else {
            $response = $response->whereRaw("YEAR(created_at) = " . (int) $year . "")->whereRaw("MONTH(created_at) = " . (int) $month . "")->count();
        }
        return $response;
    }

    public function pages() {
        return $this->hasOne('Powerpanel\CmsPage\Models\CmsPage', 'fkIntAliasId', 'intAliasId');
    }

}
