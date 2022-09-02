<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Validator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        //Schema::defaultStringLength(191);
        if (env('ENFORCE_SSL', false)) {
            //URL::forceScheme('https');
            $this->app['request']->server->set('HTTPS', true);
        }

        /**
         * This custom extension add a custom server side validation rule to check value > 0
         * @return  View
         * @since   2017-02-04
         * @author  NetQuick
         */
        Validator::extend('greater_than_zero', function ($attribute, $value, $parameters, $validator) {
            return $value > 0;
        });

        Validator::replacer('greater_than_zero', function ($message, $attribute, $rule, $parameters) {
            return str_replace(':field', '', $message);
        });

        Validator::extend('handle_xss', function ($attribute, $value, $parameters, $validator) {

            $value = html_entity_decode($value);

            $value = str_replace("&#60", "&lt;", $value);
            $value = str_replace("&#62", "&gt;", $value);
            $value = str_replace("&#38", "&amp;", $value);
            $value = str_replace("&#160", "&nbsp;", $value);
            $value = str_replace("&#162", "&cent;", $value);
            $value = str_replace("&#163", "&pound;", $value);
            $value = str_replace("&#165", "&yen;", $value);
            $value = str_replace("&#8364", "&euro;", $value);
            $value = str_replace("&#169", "&copy;", $value);
            $value = str_replace("&#174", "&reg;", $value);

            if (preg_match('/((\%3C)|<)((\%2F)|\/)*[a-z0-9\%]+((\%3E)|>)/ix', $value)) {
                return false;
            } else if (preg_match('/<img|script[^>]+src/i', $value)) {
                return false;
            } else if (preg_match('/((\%3C)|<)(|\s|\S)+((\%3E)|>)/i', $value)) {
                return false;
            } else if (strstr($value, '<') != '' || strstr($value, '>') != '' || strstr($value, '&#60') != '' || strstr($value, '&#62') != '' || strstr($value, '&#x3C') != '' || strstr($value, '&#x3E') != '') {
                return false;
            } else {
                return true;
            }
        });

        Validator::replacer('handle_xss', function ($message, $attribute, $rule, $parameters) {
            return 'Please enter valid input.';
        });

        Validator::extend('check_passwordrules', function ($attribute, $value, $parameters, $validator) {
            if (preg_match('/^(?=.*?[A-Z])(?=.*?[a-z])(?=.*?[0-9])(?=.*?[^\w\s]).{6,20}$/', $value)) {
                return true;
            } else {
                return false;
            }
        });

        Validator::replacer('check_passwordrules', function ($message, $attribute, $rule, $parameters) {
            return 'Please follow rules for password.';
        });

        Validator::extend('avoidonlyzero', function ($attribute, $value, $parameters, $validator) {
            /*code for avoid only zero value from textbox */
            /*$tempValue = $value;
            $numberPattern = '/\d+/g';
            $newVal = value.replace($numberPattern, '');
            if($newVal <= 0)
            {
            return false;
            }else{
            return true;
            }*/
            return true;
        });

        Validator::replacer('avoidonlyzero', function ($message, $attribute, $rule, $parameters) {
            /*code for message avoid only zero value from textbox */
            return 'Please enter a valid value.';
        });

        Validator::extend('valid_input', function ($attribute, $value, $parameters, $validator) {

            $regex = '/^[\x20-\x7E\n]+$/';
            if ($value != "") {
                if (!preg_match($regex, $value)) {
                    return false;
                } else {
                    return true;
                }
            } else {
                return true;
            }

        });

        Validator::replacer('valid_input', function ($message, $attribute, $rule, $parameters) {
            return 'Please enter valid input.';
        });

        Validator::extend('no_url', function ($attribute, $value, $parameters, $validator) {
            $str = $value;
            preg_match_all('/\b(?:(?:https?|ftp|file):\/\/|www\.|ftp\.)[-A-Z0-9+&@#\/%=~_|$?!:,.]*[A-Z0-9+&@#\/%=~_|$]/i', $str, $result, PREG_PATTERN_ORDER);

            if (isset($result[0][0]) && !empty($result[0][0])) {
                return false;
            } else {
                return true;
            }
        });

        Validator::replacer('no_url', function ($message, $attribute, $rule, $parameters) {
            return 'URL is not allowed.';
        });

        Validator::extend('valid_captcha', function ($attribute, $value, $parameters, $validator) {
            $payload = [
                'secret' => Config::get('Constant.GOOGLE_RECAPTCHA_SECRET'),
                'response' => $value,
            ];

            $ch = curl_init("https://www.google.com/recaptcha/api/siteverify");

            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

            $recaptchaReply = json_decode(curl_exec($ch));
            curl_close($ch);

            if (isset($recaptchaReply->success) && $recaptchaReply->success == "1") {
                return true;
            } else {
                return false;
            }
        });

        Validator::replacer('valid_captcha', function ($message, $attribute, $rule, $parameters) {
            return 'ReCaptcha verification failed.';
        });

        Validator::extend('bad_words', function ($attribute, $value, $parameters, $validator) {
            $badWords = array("nude", "naked", "sex", "porn", "porno", "sperm", "penis", "pussy", "vegina", "boobs", "asshole", "bitch", "dick");
            $str = strtolower($value);
            $result = array();
            preg_match("/\b(" . implode("|", $badWords) . ")\b/i", $str, $result);

            if (isset($result[0]) && !empty($result[0])) {
                return false;
            } else {
                return true;
            }
        });

        Validator::replacer('bad_words', function ($message, $attribute, $rule, $parameters) {
            return 'Please remove bad word/inappropriate language.';
        });

    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
