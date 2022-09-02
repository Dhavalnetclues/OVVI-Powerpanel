<?php

namespace App\Helpers;

use App\Pagehit;
use DB;

class PageHitsReport {

    static function WebHits($AliasId, $Title) {
        $Hits = Pagehit::where('fkIntAliasId', $AliasId)->count();
        $HitsData = Self::getYearHitsData($AliasId);
        $Web_Hits = '';
        if ($Hits > 0) {
            $Web_Hits.='<table style="width:100%; border:1px solid #ddd;">';
            $Web_Hits.='<tr><th style="border-top:1px solid #ddd; padding:4px 5px; text-align:center;">Month</th>';
            foreach ($HitsData as $data) {
                $Web_Hits.='<th style="border-top:1px solid #ddd;border-left:1px solid #ddd; padding:4px 5px;">' . $data->year . '</br>Desktop<strong>|</strong>Mobile</th>';
            }
            $Web_Hits.='</tr>';
            for ($i = 1; $i <= 12; $i++) {
                $Web_Hits.='<tr>';
                $Web_Hits.='<td style="border-top:1px solid #ddd; padding:4px 5px;">' . date("F", mktime(0, 0, 0, $i, 1)) . '</td>';
                foreach ($HitsData as $data) {
                    $webMonthHitsData = Pagehit::where('isWeb', 'Y')->where('fkIntAliasId', $AliasId)->whereRaw('YEAR(updated_at) = ' . $data->year)->whereRaw('month(updated_at) = ' . $i)->count();
                    $mobileMonthHitsData = Pagehit::where('isWeb', 'N')->where('fkIntAliasId', $AliasId)->whereRaw('YEAR(updated_at) = ' . $data->year)->whereRaw('month(updated_at) = ' . $i)->count();
                    $Web_Hits.='<td style="border-top:1px solid #ddd;border-left:1px solid #ddd; padding:4px 5px;text-align:center;">' . $webMonthHitsData . '<strong>|</strong>' . $mobileMonthHitsData . '</td>';
                }
                $Web_Hits.='</tr>';
            }
            $Web_Hits.='<tr>';
            $Web_Hits.='<td style = "border-top:1px solid #ddd; padding:4px 5px;"><strong>Total: ' . $Hits . '</strong></td>';
            foreach ($HitsData as $data) {
                $webTotalHitsData = Pagehit::where('isWeb', 'Y')->where('fkIntAliasId', $AliasId)->whereRaw('YEAR(updated_at) = ' . $data->year)->count();
                $mobileTotalHitsData = Pagehit::where('isWeb', 'N')->where('fkIntAliasId', $AliasId)->whereRaw('YEAR(updated_at) = ' . $data->year)->count();
                $Web_Hits.='<td style = "border-top:1px solid #ddd;border-left:1px solid #ddd; padding:4px 5px;text-align:center;"><strong>' . $webTotalHitsData . '|' . $mobileTotalHitsData . '</strong></td>';
            }
            $Web_Hits.='</tr>';
            $Web_Hits.='</table>';
        } else {
            $Web_Hits .= $Hits;
        }
        return $Web_Hits;
    }

    static function getYearHitsData($AliasId = false) {
        $response = false;
        $response = DB::table('page_hits')->select(DB::raw('YEAR(updated_at) year'))
                ->where('fkIntAliasId', $AliasId)
                ->where('chrPublish', 'Y')
                ->where('chrDelete', 'N')
                ->groupby('year')
                ->orderBy('year', 'DESC')
                ->get();
        return $response;
    }

}
