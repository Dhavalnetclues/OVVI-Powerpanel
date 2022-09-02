<?php

namespace App\Providers;

use Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use File;


class MailConfigServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        if (File::exists(app_path() . '/Helpers/MyLibrary.php') && \Schema::hasTable('general_setting')) {
            $MAILER = DB::table('general_setting')->where('fieldName', 'MAILER')->first();
            if (isset($MAILER->fieldValue)) {

                $SMTP_SERVER = DB::table('general_setting')->where('fieldName', 'SMTP_SERVER')->first();
                $SMTP_PORT = DB::table('general_setting')->where('fieldName', 'SMTP_PORT')->first();
                $SMTP_ENCRYPTION = DB::table('general_setting')->where('fieldName', 'SMTP_ENCRYPTION')->first();
                $SMTP_USERNAME = DB::table('general_setting')->where('fieldName', 'SMTP_USERNAME')->first();
                $SMTP_PASSWORD = DB::table('general_setting')->where('fieldName', 'SMTP_PASSWORD')->first();
                $SMTP_SENDER_NAME = DB::table('general_setting')->where('fieldName', 'SMTP_SENDER_NAME')->first();

                $SMTP_SENDER_EMAIL = DB::table('general_setting')->where('fieldName', 'SMTP_SENDER_EMAIL')->first();
                if (isset($SMTP_SENDER_EMAIL->fieldValue)) {
                    $SMTP_SENDER_EMAIL = \App\Helpers\MyLibrary::getDecryptedString($SMTP_SENDER_EMAIL->fieldValue);
                }

                $config = array(
                    'driver' => $MAILER->fieldValue,
                    'host' => $SMTP_SERVER->fieldValue,
                    'port' => (int) $SMTP_PORT->fieldValue,
                    'from' => array('address' => $SMTP_SENDER_EMAIL, 'name' => $SMTP_SENDER_NAME->fieldValue),
                    'encryption' => ($SMTP_ENCRYPTION->fieldValue != "null" ? $SMTP_ENCRYPTION->fieldValue : null),
                    'username' => $SMTP_USERNAME->fieldValue,
                    'password' => $SMTP_PASSWORD->fieldValue,
                    'sendmail' => '/usr/sbin/sendmail -bs',
                    'pretend' => false,
                );
                Config::set('mail', $config);
            }
        }
    }
}
