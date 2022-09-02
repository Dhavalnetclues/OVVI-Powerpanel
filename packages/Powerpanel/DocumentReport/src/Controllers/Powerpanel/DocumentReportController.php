<?php

namespace Powerpanel\DocumentReport\Controllers\Powerpanel;

use App\Http\Controllers\PowerpanelController;
use Request;
use Powerpanel\DocumentReport\Models\DocumentsReport;
use App\Helpers\Email_sender;
use Config;
use Illuminate\Support\Facades\Validator;

class DocumentReportController extends PowerpanelController {

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
        $docChartData = $this->getDocChart();
        $this->breadcrumb['title'] = trans('documentreport::template.dcumentreportModule.manageDocumentsReports');
        return view('documentreport::powerpanel.list', ['breadcrumb' => $this->breadcrumb, 'docChartData' => $docChartData]);
    }

    public function getDocChart() {
        $filter = Request::post();
        $docChartData = DocumentsReport::getDocChartData($filter);
        return $docChartData;
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
            $file = 'DOCUMENTS_REPORT_' . time() . '.' . $image_type;
            $path = Config::get('Constant.LOCAL_CDN_PATH') . '/report_img/' . $file;
            file_put_contents($path, $image_base64);
//            --
            $moduleId = Config::get('Constant.MODULE.ID');
//            --
            $year = $data['year'];
            $table = "<table border='1' style='width:100%;border-color:#ddd;font-family:Arial,sans-serif;'>
                        <tr>
                            <th colspan='5'>$year</th>
                        </tr>
                        <tr>
                            <th align='left'>Month</th>
                            <th align='center'>Views in Mobile</th> 
                            <th align='center'>Download in Mobile</th>
                            <th align='center'>Views in Desktop</th>
                            <th align='center'>Download in Desktop</th>
                        </tr>";
            
            $current_month = 1;
            $last_month = 12;

            $documentsReport = DocumentsReport::getDatareport($year, 'report');
            $chartArr = [];
            foreach ($documentsReport as $val) {
                $chartArr['intMobileViewCount'][$val['intMonth']] = !empty($val['intMobileViewCount'])?(int) $val['intMobileViewCount']:0;
                $chartArr['intMobileDownloadCount'][$val['intMonth']] = !empty($val['intMobileDownloadCount'])?(int) $val['intMobileDownloadCount']:0;
                $chartArr['intDesktopViewCount'][$val['intMonth']] = !empty($val['intDesktopViewCount'])?(int) $val['intDesktopViewCount']:0;
                $chartArr['intDesktopDownloadCount'][$val['intMonth']] = !empty($val['intDesktopDownloadCount'])?(int) $val['intDesktopDownloadCount']:0;
            }

            
            $totalintMobileViewCount = $totalintMobileDownloadCount = $totalintDesktopViewCount = $totalintintDesktopDownloadCount = 0;
            foreach (range($current_month, $last_month) as $i) {
                $intMobileViewCount = (isset($chartArr['intMobileViewCount']) && array_key_exists($i, $chartArr['intMobileViewCount'])) ? $chartArr['intMobileViewCount'][$i] : 0;
                $intMobileDownloadCount = (isset($chartArr['intMobileDownloadCount']) && array_key_exists($i, $chartArr['intMobileDownloadCount'])) ? $chartArr['intMobileDownloadCount'][$i] : 0;
                $intDesktopViewCount = (isset($chartArr['intDesktopViewCount']) && array_key_exists($i, $chartArr['intDesktopViewCount'])) ? $chartArr['intDesktopViewCount'][$i] : 0;
                $intDesktopDownloadCount = (isset($chartArr['intDesktopDownloadCount']) && array_key_exists($i, $chartArr['intDesktopDownloadCount'])) ? $chartArr['intDesktopDownloadCount'][$i] : 0;

                $month_name = date('F', mktime(0, 0, 0, $i, 1, 0));
                $table .= "<tr>
                    <td>$month_name</td>
                    <td align='center'>$intMobileViewCount</td>
                    <td align='center'>$intMobileDownloadCount</td>
                    <td align='center'>$intDesktopViewCount</td>
                    <td align='center'>$intDesktopDownloadCount</td>
                </tr>";

                $totalintMobileViewCount += $intMobileViewCount;
                $totalintMobileDownloadCount += $intMobileDownloadCount;
                $totalintDesktopViewCount += $intDesktopViewCount;
                $totalintintDesktopDownloadCount += $intDesktopDownloadCount;
            }

            $table .= "<tr>
                        <th align='left'>Total:</th>
                        <th align='center'>$totalintMobileViewCount</th>
                        <th align='center'>$totalintMobileDownloadCount</th>
                        <th align='center'>$totalintDesktopViewCount</th>
                        <th align='center'>$totalintintDesktopDownloadCount</th>
                    </tr>";
            $table .= "</table>";

            $mailReponse = Email_sender::DocsendReport($data, $file, $table, $moduleId);
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
