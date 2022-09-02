<?php

/**
 * This helper generates email sender
 * @package   Netquick
 * @version   1.00
 * @since     2016-11-14
 */

namespace App\Helpers;

use App\CommonModel;
use App\Document;
use App\DocumentsReport;
use App\Helpers\MyLibrary;
use App\Http\Controllers\Controller;
use Config;
use Illuminate\Support\Facades\Request;

class Document_hits extends Controller
{

    public static function insertHits($docId, $counterType)
    {
        $sever_info = Request::server('HTTP_USER_AGENT');
        $ip_address = MyLibrary::get_client_ip();

        $device = '';
        if (Config::get('Constant.DEVICE') == 'iPad') {
            $device = 'Y';
        } elseif (Config::get('Constant.DEVICE') == 'mobile') {
            $deviceId = explode(',', $ip_address);
            if (isset($deviceId[0])) {
                $ip_address = $deviceId[0];
            }
            $device = 'N';
        } else {
            $device = 'Y';
        }

        if (!empty($sever_info) && !empty($device) && !empty($ip_address) && !empty($docId) && !empty($counterType)) {
            $response = Document::getDocDataForHitsById($docId);
            $documentReport = DocumentsReport::where('intYear', date('Y'))
                ->where('intMonth', date('m'))
                ->first();

            if (isset($response->id)) {
                $docDataArray = [];
                $docReportDataArray = [];

                if ($counterType == "download") {
                    if ($device == "N") {
                        $docDataArray['intMobileDownloadCount'] = (int) $response->intMobileDownloadCount + 1;
                        if (!empty($documentReport)) {
                            $docReportDataArray['intMobileDownloadCount'] = (int) $documentReport->intMobileDownloadCount + 1;
                        } else {
                            $docReportDataArray['intMobileDownloadCount'] = 1;
                        }
                    } else {
                        $docDataArray['intDesktopDownloadCount'] = (int) $response->intDesktopDownloadCount + 1;
                        if (!empty($documentReport)) {
                            $docReportDataArray['intDesktopDownloadCount'] = (int) $documentReport->intDesktopDownloadCount + 1;
                        } else {
                            $docReportDataArray['intDesktopDownloadCount'] = 1;
                        }
                    }
                } else if ($counterType == "view") {
                    if ($device == "N") {
                        $docDataArray['intMobileViewCount'] = (int) $response->intMobileViewCount + 1;
                        if (!empty($documentReport)) {
                            $docReportDataArray['intMobileViewCount'] = (int) $documentReport->intMobileViewCount + 1;
                        } else {
                            $docReportDataArray['intMobileViewCount'] = 1;
                        }
                    } else {
                        $docDataArray['intDesktopViewCount'] = (int) $response->intDesktopViewCount + 1;
                        if (!empty($documentReport)) {
                            $docReportDataArray['intDesktopViewCount'] = (int) $documentReport->intDesktopViewCount + 1;
                        } else {
                            $docReportDataArray['intDesktopViewCount'] = 1;
                        }
                    }
                }

                if (!empty($docDataArray)) {
                    $whereConditions = ['id' => $docId];
                    CommonModel::updateRecords($whereConditions, $docDataArray, false, "\app\Document");
                }

                if (!empty($docReportDataArray)) {
                    if (!empty($documentReport)) {
                        DocumentsReport::where('id', $documentReport->id)
                            ->update([
                                'intMobileDownloadCount' => (isset($docReportDataArray['intMobileDownloadCount']) ? $docReportDataArray['intMobileDownloadCount'] : $documentReport->intMobileDownloadCount),
                                'intDesktopDownloadCount' => (isset($docReportDataArray['intDesktopDownloadCount']) ? $docReportDataArray['intDesktopDownloadCount'] : $documentReport->intDesktopDownloadCount),
                                'intMobileViewCount' => (isset($docReportDataArray['intMobileViewCount']) ? $docReportDataArray['intMobileViewCount'] : $documentReport->intMobileViewCount),
                                'intDesktopViewCount' => (isset($docReportDataArray['intDesktopViewCount']) ? $docReportDataArray['intDesktopViewCount'] : $documentReport->intDesktopViewCount),
                            ]);

                    } else {

                        $docReportDataArray['intYear'] = date('Y');
                        $docReportDataArray['intMonth'] = date('m');
                        DocumentsReport::insertGetId($docReportDataArray);
                    }
                }

            }
        }
    }

}
