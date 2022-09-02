<?php

namespace App;
namespace Powerpanel\DocumentReport\Models;

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

    public static function DocumentsReport($year = false, $filed = false) {
        $data = DB::table("documents_report")
            ->select(DB::raw("SUM($filed) as $filed"))
            ->where('intYear', '=', $year)
            ->orderBy('intYear', 'desc')
            ->first();
        return $data;
    }

    public static function getDatareport($year = false, $type = false) {
        $response = Self::select("intMonth", DB::raw("SUM(intMobileViewCount) as intMobileViewCount"), DB::raw("SUM(intMobileDownloadCount) as intMobileDownloadCount"), DB::raw("SUM(intDesktopViewCount) as intDesktopViewCount"), DB::raw("SUM(intDesktopDownloadCount) as intDesktopDownloadCount"));
        if($year != '') {
            if($type == 'dashboard') {
                $response = $response->where('intYear', '>=', $year);
            } else {
                $response = $response->where('intYear', '=', $year);
            }
        }
        $response = $response->groupBy('intMonth')->get();
        return $response;
    }

    public static function getDocChartData($filter = false)
    {
        $data = $chartReport = [];
        $type = isset($filter['type']) ? $filter['type'] : 0;
        $year = isset($filter['year']) ? $filter['year'] : 0;
        $year = date('Y', strtotime('-'.$year.' years'));
        
        $labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        $current_month = 1;
        $last_month = 12;

        $documentsReport = Self::getDatareport($year, $type);
        $chartArr = [];
        foreach ($documentsReport as $val) {
            $chartArr['intMobileViewCount'][$val['intMonth']] = !empty($val['intMobileViewCount'])?(int) $val['intMobileViewCount']:0;
            $chartArr['intMobileDownloadCount'][$val['intMonth']] = !empty($val['intMobileDownloadCount'])?(int) $val['intMobileDownloadCount']:0;
            $chartArr['intDesktopViewCount'][$val['intMonth']] = !empty($val['intDesktopViewCount'])?(int) $val['intDesktopViewCount']:0;
            $chartArr['intDesktopDownloadCount'][$val['intMonth']] = !empty($val['intDesktopDownloadCount'])?(int) $val['intDesktopDownloadCount']:0;
        }

        foreach (range($current_month, $last_month) as $i) {
            $data['intMobileViewCount'][$i] = (isset($chartArr['intMobileViewCount']) && array_key_exists($i, $chartArr['intMobileViewCount'])) ? $chartArr['intMobileViewCount'][$i] : 0;
            $data['intMobileDownloadCount'][$i] = (isset($chartArr['intMobileDownloadCount']) && array_key_exists($i, $chartArr['intMobileDownloadCount'])) ? $chartArr['intMobileDownloadCount'][$i] : 0;
            $data['intDesktopViewCount'][$i] = (isset($chartArr['intDesktopViewCount']) && array_key_exists($i, $chartArr['intDesktopViewCount'])) ? $chartArr['intDesktopViewCount'][$i] : 0;
            $data['intDesktopDownloadCount'][$i] = (isset($chartArr['intDesktopDownloadCount']) && array_key_exists($i, $chartArr['intDesktopDownloadCount'])) ? $chartArr['intDesktopDownloadCount'][$i] : 0;
        }

        $i=0;
        foreach($data AS $key => $value) {
            if($key == 'intMobileViewCount') {
                $name = 'Views in Mobile';
                $type = 'bar';
            } else if($key == 'intMobileDownloadCount') {
                $name = 'Downloads in Mobile';
                $type = 'area';
            } else if($key == 'intDesktopViewCount') {
                $name = 'Views in Desktop';
                $type = 'bar';
            } else if($key == 'intDesktopDownloadCount') {
                $name = 'Downloads in Desktop';
                $type = 'area';
            } else {
                $name = '';
                $type = '';
            }

            $chartReport[$i]['name'] = $name;
            $chartReport[$i]['type'] = $type;
            $chartReport[$i]['data'] = array_merge($value);
            $i++;
        }

        $dataArr = [$labels, $chartReport];
        $docChartData = json_encode($dataArr);
        return $docChartData;
    }
}
