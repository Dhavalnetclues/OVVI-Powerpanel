<?php

/**
 * This helper give description for static block section by alias.
 * @package   Netquick
 * @version   1.00
 * @since     2016-12-07
 * @author    Vishal Agrawal
 */

namespace App\Helpers;

use App\Helpers\Aws_File_helper;
use App\Helpers\MyLibrary;
use App\Image;
use Config;
use File;
use Intervention\Image\Facades\Image as resizeImage;
use Throwable;

class resize_image
{

    public static function resize($imageID = false, $width = false, $height = false)
    {
        $AWSContants = MyLibrary::getAWSconstants();
        $_APP_URL = $AWSContants['CDN_PATH'];
        try {
            if ($AWSContants['BUCKET_ENABLED']) {
                $response = $_APP_URL . $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . 'default.png';
            } else {
                $response = Config::get('Constant.ENV_APP_URL') . 'assets/images/default.png';
            }

            $images = Image::getImg($imageID);

            $folderimages = Image::getFolderImage($imageID);
            if (!empty($images) && $images->toArray() > 0) {
                if (!empty($folderimages) && isset($folderimages->varfolder) && $folderimages->varfolder == 'folder') {
                    $folderimageName = $folderimages->txtImageName;
                    $folderextension = $folderimages->varImageExtension;
                } else {
                    $imageName = $images->txtImageName;
                    $extension = $images->varImageExtension;
                }

                if (!empty($width) && !empty($height)) {
                    if (!empty($images) && $imageID != 0 && !empty($imageName) && !empty($extension) && $extension != 'svg') {
                        if ($AWSContants['BUCKET_ENABLED']) {
                            $imagePath = $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $imageName . '.' . $extension;
                            $path = 'caches/' . $width . 'x' . $height;
                        } else {
                            if (!empty($folderimages) && isset($folderimages->varfolder) && $folderimages->varfolder == 'folder') {
                                $foldername = Image::getFolderName($folderimages->fk_folder);
                                if (!empty($foldername)) {
                                    $imagePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $foldername->foldername . '/' . $folderimageName . '.' . $folderextension;
                                } else {
                                    $imagePath = '';
                                }
                            } else {
                                $imagePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $imageName . '.' . $extension;
                            }
                            $path = Config::get('Constant.LOCAL_CDN_PATH') . '/caches/' . $width . 'x' . $height;
                        }

                        if (Mylibrary::filePathExist($imagePath)) {

                            if (Mylibrary::filePathExist($path . '/' . $imageName . '.' . $extension)) {

                                if ($height) {
                                    $folderName = $width . 'x' . $height;
                                } else {
                                    $folderName = $width;
                                }

                                if ($AWSContants['BUCKET_ENABLED']) {
                                    $response = $_APP_URL . 'caches/' . $folderName . '/' . $imageName . '.' . $extension;
                                } else {
                                    if (!empty($folderimages) && isset($folderimages->varfolder) && $folderimages->varfolder == 'folder') {
                                        $foldername = Image::getFolderName($folderimages->fk_folder);
                                        if (!empty($foldername)) {
                                            $response = Config::get('Constant.ENV_APP_URL') . '/assets/images/' . $foldername->foldername . '/' . $folderimageName . '.' . $folderextension;
                                        } else {
                                            $response = '';
                                        }
                                    } else {
                                        $response = Config::get('Constant.ENV_APP_URL') . 'caches/' . $folderName . '/' . $imageName . '.' . $extension;
                                    }
                                }
                            } else {

                                if ($AWSContants['BUCKET_ENABLED']) {
                                    $saveAsLocalPath = Config::get('Constant.LOCAL_CDN_PATH') . '/awsresizefiles/' . 'temp-' . $width . '-' . $height . '-' . $imageName . '.' . $extension;
                                    Aws_File_helper::getObjectWithSaveAs($imagePath, $saveAsLocalPath);
                                    $imagePath = $saveAsLocalPath;
                                }

                                $img = resizeImage::make($imagePath);
                                $img->resize(intval($width), null, function ($constraint) {
                                    $constraint->aspectRatio();
                                    $constraint->upsize();
                                });

                                if (!is_dir($path)) {
                                    File::makeDirectory($path, 755, true, true);
                                }

                                if ($AWSContants['BUCKET_ENABLED']) {
                                    $savefilepath = Config::get('Constant.LOCAL_CDN_PATH') . '/awsresizefiles/' . 'resize-' . $width . '-' . $height . '-' . $imageName . '.' . $extension;
                                } else {
                                    if (!empty($folderimages) && isset($folderimages->varfolder) && $folderimages->varfolder == 'folder') {
                                        $foldername = Image::getFolderName($folderimages->fk_folder);
                                        if (!empty($foldername)) {
                                            $savefilepath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $foldername->foldername . '/' . $folderimageName . '.' . $folderextension;
                                        } else {
                                            $savefilepath = '';
                                        }
                                    } else {
                                        $savefilepath = $path . '/' . $imageName . '.' . $extension;
                                    }
                                }

                                if ($img->save($savefilepath)) {
                                    if ($AWSContants['BUCKET_ENABLED']) {
                                        $storeFileName = $imageName . '.' . $extension;
                                        Aws_File_helper::putObject($savefilepath, $path . '/', $storeFileName);
                                        $response = $_APP_URL . 'caches/' . $width . 'x' . $height . '/' . $imageName . '.' . $extension;
                                        unlink($saveAsLocalPath);
                                        unlink($savefilepath);
                                    } else {
                                        if (!empty($folderimages) && isset($folderimages->varfolder) && $folderimages->varfolder == 'folder') {
                                            $foldername = Image::getFolderName($folderimages->fk_folder);
                                            if (!empty($foldername)) {
                                                $response = Config::get('Constant.ENV_APP_URL') . '/assets/images/' . $foldername->foldername . '/' . $folderimageName . '.' . $folderextension;
                                            } else {
                                                $response = '';
                                            }
                                        } else {
                                            $response = Config::get('Constant.ENV_APP_URL') . '/caches/' . $width . 'x' . $height . '/' . $imageName . '.' . $extension;
                                        }
                                    }
                                }
                            }
                        }
                    } else {

                        if ($AWSContants['BUCKET_ENABLED']) {
                            $bitmapfile = $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $imageName . '.' . $extension;
                            if (Mylibrary::filePathExist($bitmapfile)) {
                                $response = $_APP_URL . $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $imageName . '.' . $extension;
                            }
                        } else {
                            if (!empty($folderimages) && isset($folderimages->varfolder) && $folderimages->varfolder == 'folder') {
                                $foldername = Image::getFolderName($folderimages->fk_folder);
                                if (!empty($foldername)) {
                                    $bitmapfile = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $foldername->foldername . '/' . $folderimageName . '.' . $folderextension;
                                } else {
                                    $bitmapfile = '';
                                }
                            } else {
                                $bitmapfile = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $imageName . '.' . $extension;
                            }

                            if (realpath($bitmapfile)) {

                                if (!empty($folderimages) && isset($folderimages->varfolder) && $folderimages->varfolder == 'folder') {
                                    $foldername = Image::getFolderName($folderimages->fk_folder);
                                    if (!empty($foldername)) {
                                        $response = Config::get('Constant.ENV_APP_URL') . '/assets/images/' . $foldername->foldername . '/' . $folderimageName . '.' . $folderextension;
                                    } else {
                                        $response = '';
                                    }
                                } else {
                                    $response = Config::get('Constant.ENV_APP_URL') . 'assets/images/' . $imageName . '.' . $extension;
                                }
                            }
                        }
                    }
                } else {

                    if ($imageID != 0 || !empty($imageID)) {
                        if ($AWSContants['BUCKET_ENABLED']) {
                            $filePath = $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $imageName . '.' . $extension;
                            if (Mylibrary::filePathExist($filePath)) {
                                $response = $_APP_URL . $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $imageName . '.' . $extension;
                            }
                        } else {
                            if (!empty($folderimages) && isset($folderimages->varfolder) && $folderimages->varfolder == 'folder') {
                                $foldername = Image::getFolderName($folderimages->fk_folder);
                                if (!empty($foldername)) {
                                    $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $foldername->foldername . '/' . $folderimageName . '.' . $folderextension;
                                } else {
                                    $filePath = '';
                                }
                            } else {
                                $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $imageName . '.' . $extension;
                            }

                            if (Mylibrary::filePathExist($filePath)) {
                                if (!empty($folderimages) && isset($folderimages->varfolder) && $folderimages->varfolder == 'folder') {
                                    $foldername = Image::getFolderName($folderimages->fk_folder);
                                    if (!empty($foldername)) {
                                        $response = Config::get('Constant.ENV_APP_URL') . '/assets/images/' . $foldername->foldername . '/' . $folderimageName . '.' . $folderextension;
                                    } else {
                                        $response = '';
                                    }
                                } else {
                                    $response = Config::get('Constant.ENV_APP_URL') . 'assets/images/' . $imageName . '.' . $extension;
                                }
                            }
                        }
                    }
                }
            }
            return $response;
        } catch (Throwable $e) {
            return false;
        }
    }

    public static function thumbImage($images = null, $width = false, $height = false)
    {

        $AWSContants = MyLibrary::getAWSconstants();
        $_APP_URL = $AWSContants['CDN_PATH'];

        if ($AWSContants['BUCKET_ENABLED']) {
            $response = $_APP_URL . $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . 'default.png';
        } else {
            $response = Config::get('Constant.ENV_APP_URL') . 'assets/images/default.png';
        }

        if (!empty($images) && isset($images->txtImageName) && isset($images->varImageExtension)) {

            $imageName = $images->txtImageName;
            $extension = $images->varImageExtension;

            $folderName = '';
            if (isset($images->folder) && !empty($images->folder)) {
                $folderName = $images->folder->foldername;
            }

            if ($AWSContants['BUCKET_ENABLED']) {

                if (!empty($width) && !empty($height)) {

                    if ($height) {
                        $sizeFolderName = $width . 'x' . $height;
                    } else {
                        $sizeFolderName = $width;
                    }

                    if (!empty($folderName)) {
                        $imagePath = $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $folderName . '/' . $imageName . '.' . $extension;
                        $cachePath = 'caches/' . $sizeFolderName . '/' . $folderName;
                    } else {
                        $imagePath = $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $imageName . '.' . $extension;
                        $cachePath = 'caches/' . $sizeFolderName;
                    }

                    if (Aws_File_helper::checkObjectExists($imagePath)) {

                        if (Aws_File_helper::checkObjectExists($cachePath.'/' . $imageName . '.' . $extension)) {

                            if (!empty($folderName)) {
                                $response = $_APP_URL . 'caches/' . $sizeFolderName . '/' . $folderName . '/' . $imageName . '.' . $extension;
                            } else {
                                $response = $_APP_URL . 'caches/' . $sizeFolderName . '/' . $imageName . '.' . $extension;
                            }

                        } else {
                            
                            $saveAsLocalPath = Config::get('Constant.LOCAL_CDN_PATH') . '/awsresizefiles/' . 'temp-' . $width . '-' . $height . '-' . $imageName . '.' . $extension;
                            Aws_File_helper::getObjectWithSaveAs($imagePath, $saveAsLocalPath);
                            $imagePath = $saveAsLocalPath;

                            $img = resizeImage::make($imagePath);
                            $img->resize(intval($width), intval($height), function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });

                            $savefilepath = Config::get('Constant.LOCAL_CDN_PATH') . '/awsresizefiles/resize-' . $width . '-' . $height . '-' . $imageName . '.' . $extension;
                            if ($img->save($savefilepath)) {
                                $storeFileName = $imageName . '.' . $extension;
                                Aws_File_helper::putObject($savefilepath, $cachePath.'/', $storeFileName);
                                if (!empty($folderName)) {
                                    $response = $_APP_URL . 'caches/' . $sizeFolderName . '/' . $folderName . '/' . $imageName . '.' . $extension;
                                } else {
                                    $response = $_APP_URL . 'caches/' . $sizeFolderName . '/' . $imageName . '.' . $extension;
                                }
                                unlink($saveAsLocalPath);
                                unlink($savefilepath);
                            }
                        }
                    }

                } else {

                    if (!empty($folderName)) {
                        $filePath = $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $folderName . '/' . $imageName . '.' . $extension;
                    } else {
                        $filePath = $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $imageName . '.' . $extension;
                    }

                    if (Mylibrary::filePathExist($filePath)) {
                        $response = $_APP_URL . $AWSContants['S3_MEDIA_BUCKET_PATH'] . '/' . $imageName . '.' . $extension;
                    }

                }

            } else {

                if (!empty($width) && !empty($height)) {

                    if ($height) {
                        $sizeFolderName = $width . 'x' . $height;
                    } else {
                        $sizeFolderName = $width;
                    }

                    if (!empty($folderName)) {
                        $imagePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $folderName . '/' . $imageName . '.' . $extension;
                        $cachePath = Config::get('Constant.LOCAL_CDN_PATH') . '/caches/' . $sizeFolderName . '/' . $folderName;
                    } else {
                        $imagePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $imageName . '.' . $extension;
                        $cachePath = Config::get('Constant.LOCAL_CDN_PATH') . '/caches/' . $sizeFolderName;
                    }

                    if (file_exists($imagePath)) {

                        if (file_exists($cachePath . '/' . $imageName . '.' . $extension)) {

                            if (!empty($folderName)) {
                                $response = Config::get('Constant.ENV_APP_URL') . 'caches/' . $sizeFolderName . '/' . $folderName . '/' . $imageName . '.' . $extension;
                            } else {
                                $response = Config::get('Constant.ENV_APP_URL') . 'caches/' . $sizeFolderName . '/' . $imageName . '.' . $extension;
                            }

                        } else {

                            $img = resizeImage::make($imagePath);
                            $img->resize(intval($width), $height, function ($constraint) {
                                $constraint->aspectRatio();
                                $constraint->upsize();
                            });

                            if (!is_dir($cachePath)) {
                                File::makeDirectory($cachePath, 755, true, true);
                            }

                            $savefilepath = $cachePath . '/' . $imageName . '.' . $extension;
                            if ($img->save($savefilepath)) {

                                if (!empty($folderName)) {
                                    $response = Config::get('Constant.ENV_APP_URL') . 'caches/' . $sizeFolderName . '/' . $folderName . '/' . $imageName . '.' . $extension;
                                } else {
                                    $response = Config::get('Constant.ENV_APP_URL') . 'caches/' . $sizeFolderName . '/' . $imageName . '.' . $extension;
                                }
                            }
                        }
                    }

                } else {

                    if (!empty($folderName)) {
                        $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $folderName . '/' . $imageName . '.' . $extension;
                    } else {
                        $filePath = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $imageName . '.' . $extension;
                    }

                    if (file_exists($filePath)) {

                        if (!empty($folderName)) {
                            $response = Config::get('Constant.ENV_APP_URL') . 'assets/images/' . $folderName . '/' . $imageName . '.' . $extension;
                        } else {
                            $response = Config::get('Constant.ENV_APP_URL') . 'assets/images/' . $imageName . '.' . $extension;
                        }
                    }
                }
            }
        }
        return $response;
    }

}
