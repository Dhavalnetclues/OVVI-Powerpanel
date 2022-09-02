<?php

/**
 * The Email_sender class handels email functions
 * configuration  process (ORM code Updates).
 * @package   Netquick powerpanel
 * @license   http://www.opensource.org/licenses/BSD-3-Clause
 * @version   1.1
 * @since     2017-08-17
 * @author    NetQuick
 */

namespace App\Helpers;

use Config;
use App\Helpers\MyLibrary;

class PlugNPay
{

    public static function Pay_Now($options = []) {
        $paymentOptionArr = '';
        $paymentOptionArr .= "publisher-name=" . Config::get('Constant.PLUGNPAY_PUBLISHER_NAME') . "&";
        $paymentOptionArr .= "publisher-password=" . Config::get('Constant.PLUGNPAY_PUBLISHER_PASSWORD') . "&";
        $paymentOptionArr .= "mode=auth&";
        $paymentOptionArr .= "card-name=" . $options['varCardHolderName'] . "&";
        $paymentOptionArr .= "card-number=" . $options['CardNumber'] . "&";
        $paymentOptionArr .= "card-cvv=" . $options['CardCVV'] . "&";
        $paymentOptionArr .= "card-exp=" . $options['CardExpiryMonth'] . "/" . $options['CardExpiryYear'] . "&";
        $paymentOptionArr .= "card-amount=" . $options['Amount'] . "&";
        $paymentOptionArr .= "email=" . $options['Email']."&";
        $paymentOptionArr .= "currency=".$options['Currency']."&";
        $paymentOptionArr .= "ipaddress=".MyLibrary::get_client_ip();

        $pnp_transaction_array = self::get_response($paymentOptionArr);

        return $pnp_transaction_array;
    }

    public static function get_response($paymentOptionArr)
    {
        $pnp_transaction_array = [];
        $pnp_ch = curl_init('https://pay1.plugnpay.com/payment/pnpremote.cgi');
        curl_setopt($pnp_ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($pnp_ch, CURLOPT_POSTFIELDS, $paymentOptionArr);
        if(env('APP_ENV') == 'local'){
            curl_setopt($pnp_ch, CURLOPT_SSL_VERIFYPEER, false);
        }
        $pnp_result_page = curl_exec($pnp_ch);
        $pnp_result_decoded = urldecode($pnp_result_page);
        $pnp_temp_array = explode('&', $pnp_result_decoded);
        if (!empty($pnp_temp_array)) {
            foreach ($pnp_temp_array as $entry) {
                if (!empty($entry)) {
                    list($name, $value) = explode('=', $entry);
                    $pnp_transaction_array[$name] = $value;
                }
            }
        }
        return $pnp_transaction_array;
    }

}
