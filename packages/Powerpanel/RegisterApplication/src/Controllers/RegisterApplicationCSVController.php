<?php

namespace Powerpanel\RegisterApplication\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\CompanyCategory\Models\CompanyCategory;
use Powerpanel\RegisterApplication\Models\RegisterApplication;
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
use Response;
use File;
use Illuminate\Support\Facades\Storage;
use App\Document;
use DB;

class RegisterApplicationCSVController extends FrontController
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

        $file = Config::get('Constant.LOCAL_CDN_PATH') . '/csv/icta_applicationregister.csv';

        $data = Excel::toArray(new DataImport, $file);
        // echo '<pre>';print_r($data[0]);die;
        $sector = 'ict';
        foreach ($data[0] as $key => $company) {
            $company['alias'] = $this->aliasGenerate($company['varTitle'])['alias'];
            $company['chrMenuDisplay'] = 'Y';
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
        $registerApplicationArr = array();
        $status = [
            8 => 'Licence Issued',
            9=> 'Licence Revoked',
            10 => 'Licence Surrendered',
            11 => 'Application Withdrawn',
            12 => 'Application Deferred',
            13 => 'Application Denied',
            15 => 'Application Revised',
            17 => 'Not Granted',
            18 => 'Application Open',
        ];

        $ModuleObj = Modules::where('varModuleName', 'register-application')->first();
        
        $registerApplicationArr['varService'] = $postArr['Fk_Services'];
        $registerApplicationArr['varCompanyId'] = stripslashes(trim($postArr['varCompanyId']));
        $registerApplicationArr['varTitle'] = stripslashes(trim($postArr['varTitle']));
        $registerApplicationArr['varContactAddress'] = $postArr['txtContact'];
        $registerApplicationArr['varWeblink1'] = !empty($postArr['varWeb1']) ? $postArr['varWeb1'] : null;
        $registerApplicationArr['varWeblink2'] = !empty($postArr['varWeb2']) ? $postArr['varWeb2'] : null;
        $registerApplicationArr['varWeblink3'] = !empty($postArr['varWeb3']) ? $postArr['varWeb3'] : null;
        $registerApplicationArr['varEmail'] = $postArr['varEmailId'];
        $registerApplicationArr['varContactPerson'] = stripslashes(trim($postArr['varContactName']));
        $registerApplicationArr['txtDescription'] = !empty($postArr['txtCurrentStatus']) ? json_encode(
        [    [
                "type" => "textarea",
                "val" => [
                    "content" => $postArr['txtCurrentStatus'],
                    "extclass" => ""
                ]
            ]
        ]
        ): null;
        $registerApplicationArr['varStatus'] = $status[$postArr['FK_Status']];
        $registerApplicationArr['intDisplayOrder'] = $postArr['intDisplayOrder'];
        $registerApplicationArr['varSector'] = 'ict';        
        $registerApplicationArr['chrMain'] = 'Y';        
        $registerApplicationArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], $ModuleObj->id, $preview);
        $registerApplicationArr['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
        $registerApplicationArr['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
        $registerApplicationArr['chrDraft'] = 'N';
        $registerApplicationArr['chrDelete'] = $postArr['chrDelete'];
        $registerApplicationArr['chrPublish'] = $postArr['chrPublish'];
        $registerApplicationArr['chrPageActive'] = 'PU';
        $registerApplicationArr['varPassword'] = '';
        $registerApplicationArr['UserID'] = 1;
        $registerApplicationArr['created_at'] = Carbon::now();

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $registerApplicationID = CommonModel::addRecord($registerApplicationArr, 'Powerpanel\RegisterApplication\Models\RegisterApplication');
        if (!empty($registerApplicationID)) {
            $id = $registerApplicationID;
            $newRegisterApplicationObj = RegisterApplication::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, $ModuleObj->id, $addlog,1);
            $logArr['varTitle'] = stripslashes(trim($postArr['varTitle']));
            Log::recordLog($logArr);
            $response = $newRegisterApplicationObj;
            return 'true';
        } else {
            return 'false';
        }

    }

}
