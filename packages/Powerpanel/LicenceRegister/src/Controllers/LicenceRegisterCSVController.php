<?php

namespace Powerpanel\LicenceRegister\Controllers;

use App\Helpers\FrontPageContent_Shield;
use App\Helpers\MyLibrary;
use App\Http\Controllers\FrontController;
use App\Http\Traits\slug;
use Powerpanel\CompanyCategory\Models\CompanyCategory;
use Powerpanel\LicenceRegister\Models\LicenceRegister;
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

class LicenceRegisterCSVController extends FrontController
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

        $file = Config::get('Constant.LOCAL_CDN_PATH') . '/csv/icta_licenceregister.csv';

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
        $licenceRegisterArr = array();

        $licencesStatus = [
            1 => 'Issued',
            2=> 'Reissued',
            3 => 'Renewed',
            4 => 'Pending',
            5 => 'Revoked',
            6 => 'Surrendered',
            7 => 'Expired',
            14 => 'Consolidated',
            16 => 'Suspended',
        ];

        // print_r($licencesStatus[$postArr['FK_Status']]);die;
        $ModuleObj = Modules::where('varModuleName', 'licence-register')->first();

        $licenceRegisterArr['varTitle'] = stripslashes(trim($postArr['varTitle']));
        $licenceRegisterArr['varCompanyId'] = stripslashes(trim($postArr['varCompanyId']));
        $licenceRegisterArr['varContactPerson'] = stripslashes(trim($postArr['varContactName']));
        $licenceRegisterArr['varEmail'] = $postArr['varEmailId'];
        $licenceRegisterArr['dtDateTime'] = !empty($postArr['dtIssueDate']) ? date('Y-m-d H:i:s', strtotime($postArr['dtIssueDate'])) : date('Y-m-d H:i:s');
        $licenceRegisterArr['varIssuenote'] = $postArr['varIssueNote'];

        if(isset($postArr['dtRenewalDate']) && !empty($postArr['dtRenewalDate']) &&  $postArr['dtRenewalDate'] != '0000-00-00 00:00:00') {
            $licenceRegisterArr['dtRenewaldate'] = $postArr['dtRenewalDate'];
            $licenceRegisterArr['chrRenewal'] = 'Y';
            $licenceRegisterArr['varRenewalNote'] = $postArr['varRenewalNote'];
        } else {
            $licenceRegisterArr['chrRenewal'] = 'N';
            $licenceRegisterArr['varRenewalNote'] = null;
            $licenceRegisterArr['dtRenewaldate'] = null;
        }

        $licenceRegisterArr['varSector'] = 'ict';
        $licenceRegisterArr['varContactAddress'] = $postArr['txtContact'];
        $licenceRegisterArr['varWeblink1'] = !empty($postArr['varWeb1']) ? $postArr['varWeb1'] : null;
        $licenceRegisterArr['varWeblink2'] = !empty($postArr['varWeb2']) ? $postArr['varWeb2'] : null;
        $licenceRegisterArr['varWeblink3'] = !empty($postArr['varWeb3']) ? $postArr['varWeb3'] : null;
        $licenceRegisterArr['varService'] = $postArr['Fk_Services'];
        $licenceRegisterArr['varStatus'] = $licencesStatus[$postArr['FK_Status']];
        $licenceRegisterArr['intDisplayOrder'] = $postArr['intDisplayOrder'];
        $licenceRegisterArr['chrMain'] = 'Y';        
        $licenceRegisterArr['intAliasId'] = MyLibrary::insertAlias($postArr['alias'], $ModuleObj->id, $preview);
        $licenceRegisterArr['varMetaTitle'] = stripslashes(trim($postArr['varMetaTitle']));
        $licenceRegisterArr['varMetaDescription'] = stripslashes(trim($postArr['varMetaDescription']));
        $licenceRegisterArr['chrDraft'] = 'N';
        $licenceRegisterArr['chrDelete'] = $postArr['chrDelete'];
        $licenceRegisterArr['chrPublish'] = $postArr['chrPublish'];
        $licenceRegisterArr['chrPageActive'] = 'PU';
        $licenceRegisterArr['varPassword'] = '';
        $licenceRegisterArr['UserID'] = 1;
        $licenceRegisterArr['created_at'] = Carbon::now();

        if ($postArr['chrMenuDisplay'] == 'D') {
            $addlog = Config::get('Constant.ADDED_DRAFT');
        } else {
            $addlog = '';
        }
        $licenceRegisterID = CommonModel::addRecord($licenceRegisterArr, 'Powerpanel\LicenceRegister\Models\LicenceRegister');
        if (!empty($licenceRegisterID)) {
            $id = $licenceRegisterID;
            $newLicenceRegisterObj = LicenceRegister::getRecordForLogById($id);
            $logArr = MyLibrary::logData($id, $ModuleObj->id, $addlog,1);
            $logArr['varTitle'] = stripslashes(trim($postArr['varTitle']));
            Log::recordLog($logArr);
            $response = $newLicenceRegisterObj;
            return 'true';
        } else {
            return 'false';
        }

    }

    public function uploadLicenceRegisterDocumentCSV() {

        $file = Config::get('Constant.LOCAL_CDN_PATH') . '/csv/icta_licencedocument.csv';

        $data = Excel::toArray(new DataImport, $file);
        // echo '<pre>';print_r($data[0]);die;
        $sector = 'ict';
        foreach ($data[0] as $key => $document) {
            $record = $this->uploadDocument($document);
            echo '<br>'.$record.'<br>';
        }
        
        echo '**DONE**';
    }

    public function uploadDocument($data, $preview = 'N') {

        // $filebaseURL = "https://www.ofreg.ky/ict/upimages/licencedocument/";
        // $fileName = $data['varFile'];

        // copy($filebaseURL.$fileName, Config::get('Constant.LOCAL_CDN_PATH') . '/documents/licence-register/'.$fileName );

        // $downloadFile = response()->download(Config::get('Constant.LOCAL_CDN_PATH') . '/documents/licence-register/'.$fileName, $fileName);

        $fileInfo = pathinfo(Config::get('Constant.LOCAL_CDN_PATH') . '/documents/licence-register/'.$data['varFile']);
        
        $licenceRecord = LicenceRegister::getRecordById($data['Fk_Licence'],true);

        
        $folderdata = Document::getFolderInfo('licence-register');

        $documentsFieldsArr['fkIntUserId'] = 1;
        $documentsFieldsArr['txtDocumentName'] = $fileInfo['filename'];
        $documentsFieldsArr['txtSrcDocumentName'] = $data['varTitle'] . '@'.$licenceRecord->varTitle;
        $documentsFieldsArr['varDocumentExtension'] = $fileInfo['extension'];
        $documentsFieldsArr['chrIsUserUploaded'] = 'Y';
        $documentsFieldsArr['varfolder'] = 'folder';
        $documentsFieldsArr['chrDelete'] = $data['chrDelete'];
        $documentsFieldsArr['chrPublish'] = $data['chrPublish'];
        $documentsFieldsArr['fk_folder'] = $folderdata->id;
        $documentsFieldsArr['created_at'] = Carbon::now();

        $documentID = CommonModel::addRecord($documentsFieldsArr, '\\App\\Document');

        $whereConditions = ['id' => $licenceRecord->id];

        if(isset($licenceRecord->fkIntDocId) && !empty($licenceRecord->fkIntDocId)) {
            $document = $licenceRecord->fkIntDocId.','. $documentID;
        } else {
            $document = $documentID;
        }

        $update = DB::table('licence_register')->where('id', $licenceRecord->id)->update(array('fkIntDocId' => $document));

        return $update;

    }


}
