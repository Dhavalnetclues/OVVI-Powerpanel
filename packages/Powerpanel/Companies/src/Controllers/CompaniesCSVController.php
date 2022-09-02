<?php

namespace Powerpanel\Companies\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\CompanyCategory\Models\CompanyCategory;
use Powerpanel\Companies\Models\Companies;
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

class CompaniesCSVController extends FrontController
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

        $file = Config::get('Constant.LOCAL_CDN_PATH') . '/csv/icta_company.csv';

        $data = Excel::toArray(new DataImport, $file);
        // echo '<pre>';print_r($data[0]);die;
        $sector = 'ict';
        foreach ($data[0] as $key => $company) {
            $company['alias'] = $this->aliasGenerate($company['varTitle'])['alias'];
            $company['chrMenuDisplay'] = 'Y';
            // print_r($company['alias']);die;
            $record = $this->insertNewRecord($company);
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

    public function insertNewRecord($postArr, $preview = 'N') {
        $response = false;
        $ModuleObj = Modules::where('varModuleName', 'companies')->first();
        $companiesArr = array();
        $companiesArr['chrMain'] = 'Y';
        $companiesArr['varTitle'] = stripslashes(trim($postArr['varTitle']));
        $companiesArr['varSector'] = 'ict';
        // $companiesArr['intDisplayOrder'] = self::swap_order_add($postArr['intDisplayOrder']);
        $companiesArr['intDisplayOrder'] = $postArr['intDisplayOrder'];
        $companiesArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], $ModuleObj->id, $preview);
        // $companiesArr['varShortDescription'] = stripslashes(trim($postArr['short_description']));
        $companiesArr['chrDraft'] = 'N';
        $companiesArr['chrPageActive'] = 'PU';
        $companiesArr['varPassword'] = '';
        $companiesArr['UserID'] = 1;
        $companiesArr['created_at'] = Carbon::now();
        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $companiesID = CommonModel::addRecord($companiesArr, 'Powerpanel\Companies\Models\Companies');
        if (!empty($companiesID)) {
            $id = $companiesID;
            $newCompanyObj = Companies::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, $ModuleObj->id, $addlog,1);
            $logArr['varTitle'] = $newCompanyObj->varTitle;
            Log::recordLog($logArr);
            $response = $newCompanyObj;
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
