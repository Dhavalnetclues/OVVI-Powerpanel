<?php

/**
 * This helper give description for static block section by alias.
 * @package   Netquick
 * @version   1.00
 * @since     2019-01-30
 * @author    NetQuick Team
 */

namespace App\Helpers;

use File;
use Config;
use DB;
use App\Document;
use App\Helpers\Mylibrary;
use App\Helpers\Aws_File_helper;

class DocumentHelper {

    static function getDocsByIds($ids = array()) {
        $response = false;
        $AWSContants = MyLibrary::getAWSconstants();
        $_APP_URL = $AWSContants['CDN_PATH'];

        if (!empty($ids) && is_array($ids)) {
            $docObj = Document::getDocDataByIds($ids);

            if (!empty($docObj)) {
                foreach ($docObj as $val) {
                    if ($AWSContants['BUCKET_ENABLED']) {
                        $saveAsLocalPath = public_path('/documents/' . $val->txtSrcDocumentName . "." . $val->varDocumentExtension);
                        $file_path = $AWSContants['S3_MEDIA_BUCKET_DOCUMENT_PATH'] . '/' . $val->txtSrcDocumentName . "." . $val->varDocumentExtension;
                        $fileExists = Mylibrary::filePathExist($file_path);
                        //Aws_File_helper::getObjectWithSaveAs($file_path, $saveAsLocalPath);
                    } else {
                        $file_path = public_path('/documents/' . $val->txtSrcDocumentName . "." . $val->varDocumentExtension);
                        $fileExists = file_exists($file_path);
                    }

                    if ($fileExists) {
                        if ($AWSContants['BUCKET_ENABLED']) {
                            $file_CDNPATH = $_APP_URL.$file_path;
                            $fileSize = self::getRemoteFilesize($file_CDNPATH);
                            //$fileSize = filesize($saveAsLocalPath);
                            //unlink($saveAsLocalPath);
                        } else {
                            $fileSize = filesize($file_path);
                        }

                        $sizeFormate = Mylibrary::format_size($fileSize);
                        $val->filesize = $fileSize;
                        $val->format = $sizeFormate;
                    } else {
                        $val->filesize = "";
                        $val->format = "";
                    }
                }
                $response = $docObj;
            }
        }

        return $response;
    }
    
    static function getRemoteFilesize($url, $formatSize = true, $useHead = true)
    {
        if (false !== $useHead) {
            stream_context_set_default(array('http' => array('method' => 'HEAD')));
        }
        $head = array_change_key_case(get_headers($url, 1));
        // content-length of download (in bytes), read from Content-Length: field
        $clen = isset($head['content-length']) ? $head['content-length'] : 0;
        // cannot retrieve file size, return "-1"
        if (!$clen) {
            return 0;
        }
        if (!$formatSize) {
            return $clen; // return size in bytes
        }
        $size = $clen;
        /*switch ($clen) {
            case $clen < 1024:
                $size = $clen .' B'; break;
            case $clen < 1048576:
                $size = round($clen / 1024, 2) .' KiB'; break;
            case $clen < 1073741824:
                $size = round($clen / 1048576, 2) . ' MiB'; break;
            case $clen < 1099511627776:
                $size = round($clen / 1073741824, 2) . ' GiB'; break;
        }*/
        return $size; // return formatted size
    }

}
