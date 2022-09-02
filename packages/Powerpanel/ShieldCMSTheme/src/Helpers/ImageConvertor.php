<?php

namespace App\Helpers;

use Image as Image_Convertor;
use Config;

class ImageConvertor {

    public static function convertImageToWebP($imageName, $extension, $quality = 80, $folder = false, $foldername = false) {
        if ($folder == 'folder') {
            $source = Config::get('Constant.CDN_PATH') .'/' . '/assets/images/' . $foldername . '/' . $imageName . '.' . $extension;
            $destination = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $foldername . '/' . $imageName . '.webp';
        } else {
            $source = Config::get('Constant.CDN_PATH') .'/' . '/assets/images/' . $imageName . '.' . $extension;
            $destination = Config::get('Constant.LOCAL_CDN_PATH') . '/assets/images/' . $imageName . '.webp';
        }

        $extension = pathinfo($source, PATHINFO_EXTENSION);

        if ($extension == 'jpeg' || $extension == 'jpg') {
            $image = imagecreatefromjpeg($source);
        } elseif ($extension == 'png') {
            $image = imagecreatefrompng($source);
        }

        return imagewebp($image, $destination, $quality);
    }

}
