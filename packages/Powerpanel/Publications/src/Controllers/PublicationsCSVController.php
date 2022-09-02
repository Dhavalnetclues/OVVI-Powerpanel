<?php

namespace Powerpanel\Publications\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\PublicationsCategory\Models\PublicationsCategory;
use Powerpanel\Publications\Models\Publications;
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

class PublicationsCSVController extends FrontController
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

        $file = Config::get('Constant.LOCAL_CDN_PATH') . '/csv/pi_publication.csv';

        $data = Excel::toArray(new DataImport, $file);
        // echo '<pre>';print_r($data[0]);die;
        $sector = 'ict';
        foreach ($data[0] as $key => $publications) {
            $publications['alias'] = $this->aliasGenerate($publications['varTitle'])['alias'];
            $publications['chrMenuDisplay'] = 'Y';
            $record = $this->insertNewRecord($publications);
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
        $count = DB::table('publications')->count();

        $displayOrderId = $count+1;

        $ModuleObj = Modules::where('varModuleName', 'publications')->first();

        if(isset($postArr['varFile']) && !empty($postArr['varFile']) && $postArr['varFile'] != 'NULL' && $postArr['varFile'] != NULL) {

            $filebaseURL = "https://www.ofreg.ky/fuel/upimages/publication/";
            $fileName = $postArr['varFile'];

            copy($filebaseURL.$fileName, Config::get('Constant.LOCAL_CDN_PATH') . '/documents/fuel-publications/'.$fileName );

            $downloadFile = response()->download(Config::get('Constant.LOCAL_CDN_PATH') . '/documents/fuel-publications/'.$fileName, $fileName);

            $fileInfo = pathinfo(Config::get('Constant.LOCAL_CDN_PATH') . '/documents/fuel-publications/'.$fileName);

            $folderdata = Document::getFolderInfo('fuel-publications');

            $documentsFieldsArr['fkIntUserId'] = 1;
            $documentsFieldsArr['txtDocumentName'] = $fileInfo['filename'];
            $documentsFieldsArr['txtSrcDocumentName'] = $fileInfo['filename'];
            $documentsFieldsArr['varDocumentExtension'] = $fileInfo['extension'];
            $documentsFieldsArr['chrIsUserUploaded'] = 'Y';
            $documentsFieldsArr['varfolder'] = 'folder';
            $documentsFieldsArr['chrDelete'] = 'N';
            $documentsFieldsArr['chrPublish'] = 'Y';
            $documentsFieldsArr['fk_folder'] = $folderdata->id;
            $documentsFieldsArr['created_at'] = Carbon::now();

            $documentID = CommonModel::addRecord($documentsFieldsArr, '\\App\\Document');

        } else {
            $documentID = null;
        }

        if(isset($postArr['fk_PublicationCategory']) && !empty($postArr['fk_PublicationCategory']) && $postArr['fk_PublicationCategory'] != 'NULL' && $postArr['fk_PublicationCategory'] != NULL) {
            $categoryID = $postArr['fk_PublicationCategory'];
            
            $oldCategory = DB::select( DB::raw("SELECT * FROM pi_publicationcategory WHERE intGlCode = $categoryID") );

            $newCategory = PublicationsCategory::where('varTitle', $oldCategory[0]->varTitle)->where('varSector', 'fuel')->get()->toArray();
            $category_id = $newCategory[0]['id'];

        } else {
            $category_id = 0;
        }

        $publicationsArr = array(
            'varSector' => 'fuel',
            'varTitle' => stripslashes(trim($postArr['varTitle'])),
            'intAliasId' => MyLibrary::insertAlias($postArr['alias'], $ModuleObj->id, $preview),
            'PublicationDate' => !empty($postArr['PublishDate']) ? date('Y-m-d H:i:s', strtotime($postArr['PublishDate'])) : date('Y-m-d H:i:s'),
            'txtCategories' => $category_id,
            'fkIntDocId' => $documentID,
            'intDisplayOrder' => $displayOrderId,
            'chrDelete' => $postArr['chrDelete'], 
            'chrDraft' => 'N',
            'chrPublish' => $postArr['chrPublish'],             
            'chrPageActive' => 'PU',
            'varPassword' => '',
            'UserID' => 1,
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
        );

        $ModuleObj = Modules::where('varModuleName', 'publications')->first();

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }

        $publicationsRecordID = CommonModel::addRecord($publicationsArr, 'Powerpanel\Publications\Models\Publications');
        if (!empty($publicationsRecordID)) {
            $id = $publicationsRecordID;
            $newPublicationsObj = Publications::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, $ModuleObj->id, $addlog,1);
            $logArr['varTitle'] = stripslashes(trim($postArr['varTitle']));
            Log::recordLog($logArr);
            $response = $newPublicationsObj;
            return 'true';
        } else {
            return 'false';
        }

    }

}
