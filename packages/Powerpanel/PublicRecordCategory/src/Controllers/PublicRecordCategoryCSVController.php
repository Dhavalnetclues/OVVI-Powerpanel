<?php

namespace Powerpanel\PublicRecordCategory\Controllers;

use App\CommonModel;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use App\Imports\DataImport;
use App\Log;
use App\Modules;
use Carbon\Carbon;
use Config;
use Excel;
use Powerpanel\PublicRecordCategory\Models\PublicRecordCategory;

class PublicRecordCategoryCSVController extends FrontController
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

    public function uploadCSV()
    {

        $file = Config::get('Constant.LOCAL_CDN_PATH') . '/csv/icta_publicrecordcategory.csv';

        $data = Excel::toArray(new DataImport, $file);
        // echo '<pre>';print_r($data[0]);die;
        $sector = 'ict';
        foreach ($data[0] as $key => $company) {
            $company['alias'] = $this->aliasGenerate($company['varTitle'])['alias'];
            $company['chrMenuDisplay'] = 'Y';
            $record = $this->insertNewRecord($company);
            echo '<br>' . $record . '<br>';
        }

        echo '**DONE**';
    }

    public function aliasGenerate($alias, $sector = "")
    {

        $unique = true;
        if (!empty($sector)) {
            $unique = false;
        }
        $slug = slug::create_slug($alias, $unique);
        $response = array('alias' => $slug[0]);
        return $response;
    }

    public function insertNewRecord($postArr, $preview = 'N')
    {
        $publicRecordCategoryArr = array();
        $ModuleObj = Modules::where('varModuleName', 'public-record-category')->first();

        $publicRecordCategoryArr = [
            'intAliasId' => MyLibrary::insertAlias($postArr['alias'], $ModuleObj->id, $preview),
            'varTitle' => stripslashes(trim($postArr['varTitle'])),
            'varSector' => 'ict',
            'intParentCategoryId' => 0,
            'chrPublish' => $postArr['chrPublish'],
            'chrDelete' => $postArr['chrDelete'],
            'chrDraft' => 'N',
            'varMetaTitle' => stripslashes(trim($postArr['varMetaTitle'])),
            'varMetaDescription' => stripslashes(trim($postArr['varMetaDescription'])),
            'chrPageActive' => 'PU',
            'varPassword' => '',
            'intDisplayOrder' => $postArr['intDisplayOrder'],
            'UserID' => 1,
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'dtDateTime' => Carbon::now(),
            'dtEndDateTime' => null,
        ];

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }

        $publicRecordCategoryID = CommonModel::addRecord($publicRecordCategoryArr, 'Powerpanel\PublicRecordCategory\Models\PublicRecordCategory');
        if (!empty($publicRecordCategoryID)) {
            $id = $publicRecordCategoryID;
            $newPublicRecordCategoryObj = PublicRecordCategory::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, $ModuleObj->id, $addlog, 1);
            $logArr['varTitle'] = stripslashes(trim($postArr['varTitle']));
            Log::recordLog($logArr);
            $response = $newPublicRecordCategoryObj;
            return 'true';
        } else {
            return 'false';
        }

    }

}
