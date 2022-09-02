<?php
/**
* This helper give loads custom icons
* @package   Netquick
* @version   1.00
* @since     2017-07-04
* @author    NetQuick
*/
namespace App\Helpers;
use File;
use Exception;
class fileLoad{
	static function load($filename) {
    $path = base_path($filename);    
    if (!File::exists($path)) {
        throw new Exception("Invalid File");
    }
    $file = File::get($path);
    return $file;
	}
}