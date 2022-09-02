<?php

namespace Powerpanel\Service\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\Service\Models\Service;
use Powerpanel\CmsPage\Models\CmsPage;
use Request;
use Config;
use App\Alias;
use App\CommonModel;
use Excel;
use Carbon\Carbon;
use App\Modules;
use App\Imports\DataImport;
use App\Log;
use Powerpanel\ServiceCategory\Models\ServiceCategory;

class ServiceCSVController extends FrontController
{

    use slug;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function uploadCSV() {

        $file = Config::get('Constant.LOCAL_CDN_PATH') . '/csv/icta_networkservice.csv';

        $data = Excel::toArray(new DataImport, $file);
        // echo '<pre>';print_r($data[0]);die;
        $sector = 'ict';
        foreach ($data[0] as $key => $service) {
            $service['alias'] = 0;
            // $this->aliasGenerate($service['varName'])['alias'];
            $service['chrMenuDisplay'] = 'Y';
            $record = $this->insertNewRecord($service);
            echo '<br>'.$record.'<br>';
        }
        
        echo '**DONE**';
    }

    public function aliasGenerate($alias,$sector="")
    {

        $unique = true;
        if (!empty($sector)) {
            $unique = false;
        }
        $slug = slug::create_slug($alias, $unique);
        $response = array('alias' => $slug[0]);
        return $response;
    }

    public function insertNewRecord($data, $preview = 'N') {
        $response = false;
        $ModuleObj = Modules::where('varModuleName', 'service')->first();
        $serviceArr = array();

        $serviceArr['varTitle'] = stripslashes(trim($data['varName']));
        $serviceArr['intAliasId'] = 0;
        $serviceArr['serviceCode'] = $data['varCode'];
        $serviceArr['applicationFee'] = !empty($data['intFee']) ? $data['intFee'] : null;
        $serviceArr['noteTitle'] = !empty($data['varNoteTitle']) ? $data['varNoteTitle'] : null;
        $serviceArr['noteLink'] = !empty($data['varLink']) ? $data['varLink'] : null;

        $serviceArr['txtDescription'] = !empty($data['txtComment']) ? json_encode(
        [
            [
                "type" => "textarea",
                "val" => [
                    "content" => $data['txtComment'],
                    "extclass" => ""
                ]
            ]
        ]): null;

        $serviceArr['intFKCategory'] = isset($data['fkServiceCategory']) ? $data['fkServiceCategory'] : null;
        $serviceArr['intDisplayOrder'] = $data['intDisplayOrder'];
        $serviceArr['chrServiceFees'] = $data['chrDisplayServices'];

        $serviceArr['chrMain'] = 'Y';
        $serviceArr['varSector'] = 'ict';
        $serviceArr['chrDraft'] = 'N';
        $serviceArr['chrPageActive'] = 'PU';
        $serviceArr['varPassword'] = '';
        $serviceArr['UserID'] = 1;
        $serviceArr['created_at'] = Carbon::now();
        
        if ($data['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $serviceID = CommonModel::addRecord($serviceArr, 'Powerpanel\Service\Models\Service');
        if (!empty($serviceID)) {
            $id = $serviceID;
            $newServiceObj = Service::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, $ModuleObj->id, $addlog,1);
            $logArr['varTitle'] = $newServiceObj->varTitle;
            Log::recordLog($logArr);
            $response = $newServiceObj;
            return 'true';
        } else {
            return 'false';
        }

    }

    function csvToArray($filename = '', $delimiter = ',') {

        if (!file_exists($filename) || !is_readable($filename))
            return false;

        $header = null;
        $data = array();
        if (($handle = fopen($filename, 'r')) !== false)
        {
            while (($row = fgetcsv($handle, 1000, $delimiter)) !== false)
            {
                if (!$header)
                    $header = $row;
                else
                    $data[] = array_combine($header, $row);
            }
            fclose($handle);
        }
        echo '<pre>';print_r($data);die;
        return $data;
    }


}
