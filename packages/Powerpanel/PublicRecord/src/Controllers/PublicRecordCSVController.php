<?php

namespace Powerpanel\PublicRecord\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\PublicRecordCategory\Models\PublicRecordCategory;
use Powerpanel\PublicRecord\Models\PublicRecord;
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

class PublicRecordCSVController extends FrontController
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

        $file = Config::get('Constant.LOCAL_CDN_PATH') . '/csv/icta_publicrecord.csv';

        $data = Excel::toArray(new DataImport, $file);
        // echo '<pre>';print_r($data[0]);die;
        $sector = 'ict';
        foreach ($data[0] as $key => $company) {
            // $company['alias'] = $this->aliasGenerate($company['varTitle'])['alias'];
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

        if(isset($postArr['varPdfFile']) && !empty($postArr['varPdfFile']) && $postArr['varPdfFile'] != 'NULL' && $postArr['varPdfFile'] != NULL) {

            // $filebaseURL = "https://www.ofreg.ky/ict/upimages/publicrecord/";
            // $fileName = $postArr['varPdfFile'];

            // copy($filebaseURL.$fileName, Config::get('Constant.LOCAL_CDN_PATH') . '/documents/public-record-of-key-topics/'.$fileName );

            // $downloadFile = response()->download(Config::get('Constant.LOCAL_CDN_PATH') . '/documents/public-record-of-key-topics/'.$fileName, $fileName);

            $fileInfo = pathinfo(Config::get('Constant.LOCAL_CDN_PATH') . '/documents/public-record-of-key-topics/'.$postArr['varPdfFile']);

            $folderdata = Document::getFolderInfo('public-record-of-key-topics');

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
        
        $publicRecordArr = array(
            'varTitle' => stripslashes(trim($postArr['varTitle'])),
            'dtDateTime' => !empty($postArr['StartDate']) ? date('Y-m-d H:i:s', strtotime($postArr['StartDate'])) : date('Y-m-d H:i:s'),
            'txtCategories' => $postArr['fk_PublicCategory'],
            'varAuthor' => $postArr['varAuthor'],
            'chrDelete' => $postArr['chrDelete'], 
            'chrDraft' => 'N',
            'chrPublish' => $postArr['chrPublish'], 
            'fkIntDocId' => $documentID,
            'varSector' => 'ict',
            'chrPageActive' => 'PU',
            'varPassword' => '',
            'UserID' => 1,
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
        );

        $ModuleObj = Modules::where('varModuleName', 'public-record')->first();

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }

        $publicRecordID = CommonModel::addRecord($publicRecordArr, 'Powerpanel\PublicRecord\Models\PublicRecord');
        if (!empty($publicRecordID)) {
            $id = $publicRecordID;
            $newPublicRecordObj = PublicRecord::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, $ModuleObj->id, $addlog,1);
            $logArr['varTitle'] = stripslashes(trim($postArr['varTitle']));
            Log::recordLog($logArr);
            $response = $newPublicRecordObj;
            return 'true';
        } else {
            return 'false';
        }

    }

}
