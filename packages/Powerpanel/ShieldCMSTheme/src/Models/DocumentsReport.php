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

}
