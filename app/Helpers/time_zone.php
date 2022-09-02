<?php

/**
 * This helper give description for static block section by alias.
 * @package   Netquick
 * @version   1.00
 * @since     2016-12-07
 * @author    Vishal Agrawal
 */

namespace App\Helpers;

use Auth;
use Illuminate\Contracts\Auth\Guard;
use Config;
use DB;

class time_zone {

    static function time_zone() {
        if (config::get('Constant.DEFAULT_TIME_ZONE')) {
            date_default_timezone_set(Config::get('Constant.DEFAULT_TIME_ZONE'));
            Config::set('app.timezone', Config::get('Constant.DEFAULT_TIME_ZONE'));
        } else {
            date_default_timezone_set('Asia/Kolkata');
            Config::set('app.timezone', 'Asia/Kolkata');
        }
    }

}
