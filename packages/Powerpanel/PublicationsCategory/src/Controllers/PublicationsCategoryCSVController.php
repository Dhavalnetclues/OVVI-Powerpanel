<?php

namespace Powerpanel\PublicationsCategory\Controllers;

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
use Powerpanel\PublicationsCategory\Models\PublicationsCategory;
use DB;

class PublicationsCategoryCSVController extends FrontController
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

        $file = Config::get('Constant.LOCAL_CDN_PATH') . '/csv/pi_publicationcategory.csv';

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

    public function insertNewRecord($data, $preview = 'N')
    {
        $count = DB::table('publications_category')->count();

        $displayOrderId = $count+1;

        $publicationsCategoryArr = array();
        $ModuleObj = Modules::where('varModuleName', 'publications-category')->first();

        $publicationsCategoryArr = [
            'intAliasId' => MyLibrary::insertAlias($data['alias'], $ModuleObj->id, $preview),
            'varTitle' => stripslashes(trim($data['varTitle'])),
            'intParentCategoryId' => $data['fk_ParentpageGlCode'],
            'varSector' => 'fuel',
            'chrPublish' => $data['chrPublish'],
            'chrDelete' => $data['chrDelete'],
            'chrDraft' => 'N',
            'varMetaTitle' => stripslashes(trim($data['varMetaTitle'])),
            'varMetaDescription' => stripslashes(trim($data['varMetaDescription'])),
            'chrPageActive' => 'PU',
            'varPassword' => '',
            'intDisplayOrder' => $displayOrderId,
            'UserID' => 1,
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'dtDateTime' => Carbon::now(),
            'dtEndDateTime' => null,
        ];


        if ($data['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }

        $publicationsCategoryID = CommonModel::addRecord($publicationsCategoryArr, 'Powerpanel\PublicationsCategory\Models\PublicationsCategory');
        if (!empty($publicationsCategoryID)) {
            $id = $publicationsCategoryID;
            $newPublicationsCategoryObj = PublicationsCategory::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, $ModuleObj->id, $addlog, 1);
            $logArr['varTitle'] = stripslashes(trim($data['varTitle']));
            Log::recordLog($logArr);
            $response = $newPublicationsCategoryObj;
            return 'true';
        } else {
            return 'false';
        }

    }

}
