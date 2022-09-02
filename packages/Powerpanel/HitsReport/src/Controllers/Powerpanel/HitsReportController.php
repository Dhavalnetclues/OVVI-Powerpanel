<?php

namespace Powerpanel\HitsReport\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Request;
use Powerpanel\HitsReport\Models\HitsReport;
use App\Helpers\Email_sender;
use Config;
use Illuminate\Support\Facades\Validator;


class HitsReportController extends PowerpanelController {

    /**
     * Create a new Dashboard controller instance.
     *
     * @return void
     */
    public function __construct() {
        parent::__construct();
        if (isset($_COOKIE['locale'])) {
            app()->setLocale($_COOKIE['locale']);
        }
    }

    public function index() {
        $hits_web_mobile = $this->getPageHitChart();
        $this->breadcrumb['title'] = trans('hitsreport::template.hitsreportModule.manageHitsReports');
        return view('hitsreport::powerpanel.list', ['breadcrumb' => $this->breadcrumb, 'hits_web_mobile' => $hits_web_mobile]);
    }

    public function getPageHitChart() {
        $filter = Request::post();
        $data = $chartReport = [];
        $year = isset($filter['year']) ? $filter['year'] : date("Y");
        
        $labels = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        $current_month = 1;
        $last_month = 12;

        //web hits report
        $webHitsReport = HitsReport::getHitsreport($year, 'Y');
        $webArr = [];
        foreach ($webHitsReport as $val) {
            $webArr['webCount'][$val['month']] = !empty($val['hits'])?(int) $val['hits']:0;
        }
        foreach (range($current_month, $last_month) as $i) {
            $data['webCount'][$i] = (isset($webArr['webCount']) && array_key_exists($i, $webArr['webCount'])) ? $webArr['webCount'][$i] : 0;
        }

        //mobile hits report
        $mobileHitsReport = HitsReport::getHitsreport($year, 'N');
        $mobileArr = [];
        foreach ($mobileHitsReport as $val) {
            $mobileArr['mobileCount'][$val['month']] = !empty($val['hits'])?(int) $val['hits']:0;
        }
        foreach (range($current_month, $last_month) as $i) {
            $data['mobileCount'][$i] = (isset($mobileArr['mobileCount']) && array_key_exists($i, $mobileArr['mobileCount'])) ? $mobileArr['mobileCount'][$i] : 0;
        }

        $i=0;
        foreach($data AS $key => $value) {
            if($key == 'webCount') {
                $name = 'Web';
                $type = 'bar';
            } else if($key == 'mobileCount') {
                $name = 'Mobile';
                $type = 'bar';
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
        $hitsChartData = json_encode($dataArr);
        return $hitsChartData;
    }

    public function getSendChart(Request $request) {
        $returnArray = array("success" => "0", "msg" => "something Went Wrong");
        $data = Request::all();
        $messsages = array(
            'Report_Name.required' => 'Name is required',
            'Report_email.required' => 'Email is required',
        );
        $rules = array(
            'Report_Name' => 'required',
            'Report_email' => 'required',
        );

        $validator = Validator::make($data, $rules, $messsages);
        if ($validator->passes()) {
            $img = $data['chart_div'];
            $image_parts = explode(";base64,", $img);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = 'PAGE_HITS_' . time() . '.' . $image_type;
            $path = Config::get('Constant.LOCAL_CDN_PATH') . '/report_img/' . $file;
            file_put_contents($path, $image_base64);

            $moduleId = Config::get('Constant.MODULE.ID');
            $year = $data['year'];
            $table = "<table border='1' style='width:100%;border-color:#ddd;font-family:Arial,sans-serif;'>
                    <tr>
                        <th colspan='3'>$year</th>
                    </tr>
                    <tr>
                        <th align='left'>Month</th>
                        <th align='center'>Web</th> 
                        <th align='center'>Mobile</th>
                    </tr>";
            
            $current_month = 1;
            $last_month = 12;
    
            //web hits report
            $webHitsReport = HitsReport::getHitsreport($year, 'Y');
            $webArr = [];
            foreach ($webHitsReport as $val) {
                $webArr['webCount'][$val['month']] = !empty($val['hits'])?(int) $val['hits']:0;
            }
            //mobile hits report
            $mobileHitsReport = HitsReport::getHitsreport($year, 'N');
            $mobileArr = [];
            foreach ($mobileHitsReport as $val) {
                $mobileArr['mobileCount'][$val['month']] = !empty($val['hits'])?(int) $val['hits']:0;
            }
            
            $hits_web_sum = $Mobile_web_sum = 0;
            foreach (range($current_month, $last_month) as $i) {
                $month_name = date('F', mktime(0, 0, 0, $i, 1, 0));

                $hits_web = (isset($webArr['webCount']) && array_key_exists($i, $webArr['webCount'])) ? $webArr['webCount'][$i] : 0;
                $Mobile_web = (isset($mobileArr['mobileCount']) && array_key_exists($i, $mobileArr['mobileCount'])) ? $mobileArr['mobileCount'][$i] : 0;

                $table .= "<tr>
                <td>$month_name</td>
                    <td align='center'>$hits_web</td>
                    <td align='center'>$Mobile_web</td>
                </tr>";

                $hits_web_sum += $hits_web;
                $Mobile_web_sum += $Mobile_web;
            }
            $table .= "<tr>
                        <th align='left'>Total:</th>
                        <th align='center'>$hits_web_sum</th>
                        <th align='center'>$Mobile_web_sum</th>
                    </tr>";
            $table .= "</table>";

            $mailReponse = Email_sender::sendReport($data, $file, $table, $moduleId);
            if ($mailReponse == true) {
                $returnArray = array("success" => "1", "msg" => "Report Mail Sent");
            } else {
                $returnArray = array("success" => "0", "msg" => "Mail Not Sent,Please Try again later");
            }
        } else {
            $returnArray = array("success" => "0", "msg" => "Please fill required fields");
        }
        echo json_encode($returnArray);
        exit;
    }

}
