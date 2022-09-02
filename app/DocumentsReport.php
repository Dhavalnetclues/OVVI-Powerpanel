<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use DB;

class DocumentsReport extends Model {

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'documents_report';
    protected $fillable = [
        'id',
        'intYear',
        'intMonth',
        'intMobileViewCount',
        'intMobileDownloadCount',
        'intDesktopViewCount',
        'intDesktopDownloadCount'
    ];


    public static function getData($year = false, $month = false, $filed = false) {
        $response = Self::select($filed);
        $response = $response->where('intYear', '=', $year)->where('intMonth', '=', $month)->first();
        return $response;
    }

    public static function getDatareport($year = false) {
        $response = Self::select("intMonth", DB::raw("SUM(intMobileViewCount) as intMobileViewCount"), DB::raw("SUM(intMobileDownloadCount) as intMobileDownloadCount"), DB::raw("SUM(intDesktopViewCount) as intDesktopViewCount"), DB::raw("SUM(intDesktopDownloadCount) as intDesktopDownloadCount"));
        if($year != '') {
            $response = $response->where('intYear', '>=', $year);
        }
        $response = $response->groupBy('intMonth')->get();
        return $response;
    }

    public static function DocumentsReport($year = false, $filed = false) {
        $data = DB::table("documents_report")
                ->select(DB::raw("SUM($filed) as $filed"))
                ->where('intYear', '=', $year)
                ->orderBy('intYear', 'desc')
                ->first();
        return $data;
    }

}
