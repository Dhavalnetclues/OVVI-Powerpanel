<?php

/**
 * This helper give description for static block section by alias.
 * @package   Netquick
 * @version   1.00
 * @since     2019-01-25
 * @author    NetQuick Team
 */

namespace App\Helpers;

use File;
use Config;
use DB;
use Aws\Laravel\AwsFacade as AWS;
use Aws\S3\Exception\S3Exception;
use Aws\CloudFront\CloudFrontClient;
use Aws\CloudFront\Exception\CloudFrontException;

class Aws_File_helper {

    /**
     * This method retrun AWS Bucket Info
     * @return  bucketName
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function getBucketName() {
        return Config::get('Constant.BUCKET_NAME');
    }

    /**
     * This method retrun AWS Client Info
     * @return  AWS Client
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function getAwsClient() {
        return AWS::createClient('s3');
    }

    /**
     * This method retrun AWS CloudFront Client Info
     * @return  AWS Client
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function getAwsCloudFrontClient() {
        return AWS::createClient('CloudFront');
    }

    /**
     * This method retrun AWS File Put
     * @return  AWS file transfer
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function putObject($sourceFilePath = '', $BucketFolder = '', $storeFileName = false) {
        $keyName = basename($sourceFilePath);
        $bucketObjKey = $BucketFolder . $keyName;
        if ($storeFileName) {
            $bucketObjKey = $BucketFolder . $storeFileName;
        }
        try {
            $s3 = self::getAwsClient();
            $array = $s3->putObject(array(
                'Bucket' => self::getBucketName(),
                'Key' => $bucketObjKey,
                'SourceFile' => $sourceFilePath,
            ));
        } catch (S3Exception $e) {
            //echo $e->getMessage();
            return false;
        } catch (Exception $e) {
            //echo $e->getMessage();
            return false;
        }
        return true;
    }

    /**
     * This method retrun AWS File Delete
     * @return  AWS file transfer
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function deleteObject($FilePath = '') {
        try {
            $s3 = self::getAwsClient();
            $result = $s3->deleteObject(array(
                'Bucket' => self::getBucketName(),
                'Key' => $FilePath
            ));
            return true;
        } catch (S3Exception $e) {
            return false;
            //echo $e->getMessage();
        } catch (Exception $e) {
            return false;
            //echo $e->getMessage();
        }
    }

    /**
     * This method retrun AWS File Object
     * @return  AWS file transfer
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function getObject($FilePath = '') {
        try {
            $s3 = self::getAwsClient();
            return $s3->getObject(array(
                        'Bucket' => self::getBucketName(),
                        'Key' => $FilePath
            ));
            //return $s3->doesObjectExist(self::getBucketName(),$FilePath);
        } catch (S3Exception $e) {
            return false;
            //echo $e->getMessage();
        } catch (Exception $e) {
            return false;
            //echo $e->getMessage();
        }
    }

    static function getObject_new($FilePath = '') {
        try {
            $s3 = self::getAwsClient();
            $result = $s3->getObject(array(
                'Bucket' => self::getBucketName(),
                'Key' => $FilePath,
                'ResponseContentType' => 'application/pdf',
                'ResponseCacheControl' => 'No-cache',
            ));
            header("Content-Type: {$result['ContentType']}");
            return $result['Body'];
            exit;
            //return $s3->doesObjectExist(self::getBucketName(),$FilePath);
        } catch (S3Exception $e) {
            return false;
            //echo $e->getMessage();
        } catch (Exception $e) {
            return false;
            //echo $e->getMessage();
        }
    }

    /**
     * This method retrun AWS File Object
     * @return  AWS file transfer
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function getOnlyObjectFileContent($FilePath = '') {
        try {
            $s3 = self::getAwsClient();
            $result = $s3->getObject(array(
                'Bucket' => self::getBucketName(),
                'Key' => $FilePath,
                'ResponseContentType' => 'text/plain',
                'ResponseCacheControl' => 'No-cache',
            ));
            return $result['Body'];
            //return $s3->doesObjectExist(self::getBucketName(),$FilePath);
        } catch (S3Exception $e) {
            return '';
            //echo $e->getMessage();
        } catch (Exception $e) {
            return '';
            //echo $e->getMessage();
        }
    }

    /**
     * This method retrun AWS File Object
     * @return  AWS file transfer
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function getObjectWithSaveAs($FilePath = '', $saveAsPath = '') {
        try {
            $s3 = self::getAwsClient();
            return $s3->getObject(array(
                        'Bucket' => self::getBucketName(),
                        'Key' => $FilePath,
                        'SaveAs' => $saveAsPath
            ));
            //return $s3->doesObjectExist(self::getBucketName(),$FilePath);
        } catch (S3Exception $e) {
            return false;
            //echo $e->getMessage();
        } catch (Exception $e) {
            return false;
            //echo $e->getMessage();
        }
    }

    /**
     * This method retrun AWS File Object
     * @return  AWS file transfer
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function checkObjectExists($FilePath = '') {
        try {
            $s3 = self::getAwsClient();
            return $s3->doesObjectExist(self::getBucketName(), $FilePath);
        } catch (S3Exception $e) {
            return false;
            //echo $e->getMessage();
        } catch (Exception $e) {
            return false;
            //echo $e->getMessage();
        }
    }

    /**
     * This method retrun AWS File And Folder Inavalidation
     * @return  AWS file transfer
     * @since   2019-01-25
     * @author  NetQuick
     */
    static function createInvalidation($path = '') {
        try {
            $CloudFrontClient = self::getAwsCloudFrontClient();
            $result = $CloudFrontClient->createInvalidation([
                'DistributionId' => 'E3CHDLS2R3LN5W', // REQUIRED
                'InvalidationBatch' => [ // REQUIRED
                    'CallerReference' => time(), // REQUIRED
                    'Paths' => array(
                        'Quantity' => 1,
                        'Items' => array($path)
                    ),
                ],
            ]);
        } catch (S3Exception $e) {
            return false;
            //echo $e->getMessage();
        } catch (Exception $e) {
            return false;
            //echo $e->getMessage();
        }
    }

}
