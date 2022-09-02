<?php

namespace App\Console\Commands;

use App\GeneralSettings;
use App\Modules;
use Illuminate\Console\Command;

class MagicUplaod extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'magic-uplaod';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Publish content on page';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $username = '';
        $websiteEmailPwd = '';
        $assignedEmailArr = array();

        $websiteEmailObj = GeneralSettings::where('fieldName', 'Magic_Receive_Email')->first();
        if (isset($websiteEmailObj->fieldValue) && !empty($websiteEmailObj->fieldValue)) {
            $username = $websiteEmailObj->fieldValue;
        }

        $websiteEmailPasswordObj = GeneralSettings::where('fieldName', 'Magic_Receive_Password')->first();
        if (isset($websiteEmailPasswordObj->fieldValue) && !empty($websiteEmailPasswordObj->fieldValue)) {
            $password = $websiteEmailPasswordObj->fieldValue;
        }

        $assignedEmail = GeneralSettings::where('fieldName', 'Magic_Send_Email')->first();
        if (!empty($assignedEmail)) {
            $assignedEmail = str_replace(' ', '', $assignedEmail->fieldValue);
            $assignedEmailArr = explode(',', $assignedEmail);
        }

        $moduleObj = null;
        $publishModuleObj = GeneralSettings::where('fieldName', 'PUBLISH_CONTENT_MODULE')->first();
        if (isset($publishModuleObj->fieldValue) && !empty($publishModuleObj->fieldValue)) {
            $moduleObj = Modules::where('id', $publishModuleObj->fieldValue)->first();
        }

        $hostname = '{imap.gmail.com:993/imap/ssl}INBOX';
        $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Gmail: ' . imap_last_error());
        $emails = imap_search($inbox, 'ALL');

        if ($emails) {

            $output = '';
            rsort($emails);

            foreach ($emails as $email_number) {

                $overview = imap_fetch_overview($inbox, $email_number, 0);
                $message = imap_fetchbody($inbox, $email_number, 1.2);
                $header = imap_headerinfo($inbox, $email_number);
                $fromid = $header->from[0]->mailbox . "@" . $header->from[0]->host;

                if (in_array($fromid, $assignedEmailArr)) {

                    $news = $DB_PREFIX . 'news';
                    $selectquery = "select * from " . $news . " where varTitle='" . $authdata[0] . "'";
                    $newsquery = mysqli_query($connect, $selectquery);
                    $rowcount = mysqli_num_rows($newsquery);

                    $id1 = '';
                    if ($rowcount == 0) {

                        $structure = imap_fetchstructure($inbox, $email_number);
                        $attachments = array();
                        if (isset($structure->parts) && count($structure->parts)) {
                            for ($i = 0; $i < count($structure->parts); $i++) {
                                $attachments[$i] = array(
                                    'is_attachment' => false,
                                    'filename' => '',
                                    'name' => '',
                                    'attachment' => '');

                                if ($structure->parts[$i]->ifdparameters) {
                                    foreach ($structure->parts[$i]->dparameters as $object) {
                                        if (strtolower($object->attribute) == 'filename') {
                                            $attachments[$i]['is_attachment'] = true;
                                            $attachments[$i]['filename'] = $object->value;
                                        }
                                    }
                                }

                                if ($structure->parts[$i]->ifparameters) {
                                    foreach ($structure->parts[$i]->parameters as $object) {
                                        if (strtolower($object->attribute) == 'name') {
                                            $attachments[$i]['is_attachment'] = true;
                                            $attachments[$i]['name'] = $object->value;
                                        }
                                    }
                                }

                                if ($attachments[$i]['is_attachment']) {
                                    $attachments[$i]['attachment'] = imap_fetchbody($inbox, $email_number, $i + 1);
                                    if ($structure->parts[$i]->encoding == 3) { // 3 = BASE64
                                        $attachments[$i]['attachment'] = base64_decode($attachments[$i]['attachment']);
                                    } elseif ($structure->parts[$i]->encoding == 4) { // 4 = QUOTED-PRINTABLE
                                        $attachments[$i]['attachment'] = quoted_printable_decode($attachments[$i]['attachment']);
                                    }
                                }
                            }
                        }

                        if (count($attachments) != 0) {
                            $id = array();
                            foreach ($attachments as $at) {
                                if ($at['is_attachment'] == 1) {
                                    $filename = explode(".", $at['filename']);
                                    $file = time() . $at['filename'];
                                    $path = 'D:/wamp64/www/goverment-portal-cms/public_html/documents/' . $file;
                                    file_put_contents($path, $at['attachment']);

                                    $documentdata = $DB_PREFIX . 'documents';
                                    $docsql = "INSERT INTO " . $documentdata . " (id, fkIntUserId, txtDocumentName,intMobileViewCount,intDesktopViewCount,intMobileDownloadCount,intDesktopDownloadCount,txtSrcDocumentName,varDocumentExtension,chrIsUserUploaded,chrPublish,chrDelete,created_at,updated_at) VALUES ('','1','" . time() . $filename[0] . "','','','','','" . time() . $filename[0] . "','" . $filename[1] . "','Y','Y','N','" . date("Y-m-d H:i:s", $header->udate) . "','" . date("Y-m-d H:i:s", $header->udate) . "')";
                                    mysqli_query($connect, $docsql);
                                    $listid = mysqli_insert_id($connect);
                                    array_push($id, $listid);
                                    $id1 = implode(',', $id);
                                }
                            }
                        }
                        $sql = "INSERT INTO " . $news . " (id, fkMainRecord, intAliasId,varTitle,fkIntDocId,txtCategories,varExternalLink,txtDescription,chrEndDate,dtDateTime,dtEndDateTime,chrPublish,chrDelete,varMetaTitle,varMetaKeyword,varShortDescription,varMetaDescription,chrMain,chrAddStar,chrApproved,intApprovedBy,chrIsPreview,UserID,chrRollBack,chrLetest,intSearchRank,created_at,updated_at) VALUES ('','','','" . $authdata[0] . "','" . $id1 . "','','','" . strip_tags($message) . "','','','','Y','N','" . $authdata[0] . "','','','" . $authdata[0] . "','Y','N','N','0','N','0','N','N','2','" . date("Y-m-d H:i:s", $header->udate) . "','')";
                        mysqli_query($connect, $sql);
                        imap_mail_move($inbox, $email_number, '[Gmail]/Trash');

                    } else {
                        imap_mail_move($inbox, $email_number, '[Gmail]/Trash');
                    }
                }
            }
        }
    }
}
