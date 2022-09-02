<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class GeneralSettingsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $logo = DB::table('image')->select('id', 'txtImageName')->where('txtImageName', '=', 'logo')->first();
        DB::table('general_setting')->insert([
            'fieldName' => 'SITE_NAME',
            'fieldValue' => 'NetQuick',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'FRONT_LOGO_ID',
            'fieldValue' => $logo->id,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_ADMIN_EMAIL',
            'fieldValue' => MyLibrary::getEncryptedString('demo1.netclues@gmail.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_CC_EMAIL',
            'fieldValue' => MyLibrary::getEncryptedString('demo1.netclues@gmail.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_BCC_EMAIL',
            'fieldValue' => MyLibrary::getEncryptedString('demo1.netclues@gmail.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_REPLYTO_EMAIL',
            'fieldValue' => MyLibrary::getEncryptedString('demo1.netclues@gmail.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_BANNER_TITLE',
            'fieldValue' => 'DEFAULT TITLE',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_BANNER_SUBTITLE',
            'fieldValue' => 'DEFAULT SUB TITLE',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_HOME_BANNER',
            'fieldValue' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_INNER_BANNER',
            'fieldValue' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'MAILER',
            'fieldValue' => 'mail_api',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'MAIL_API_URL',
            'fieldValue' => 'https://www.cluesconnect.com/emailservice/email',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'MAIL_API_USERNAME',
            'fieldValue' => 'NetcluesAdmin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'MAIL_API_PASSWORD',
            'fieldValue' => 'NetcluesAdm!N$123#',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'MAIL_API_TOKEN',
            'fieldValue' => 'de3fe1e9991d5f0686f057e1583119be',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'MAIL_API_SITE_ID',
            'fieldValue' => '38',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_SERVER',
            'fieldValue' => 'popeye.netcluescloud.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_USERNAME',
            'fieldValue' => 'netquick@websiteinquiries.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_PASSWORD',
            'fieldValue' => '7ZDXtpETmmnm',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_ENCRYPTION',
            'fieldValue' => 'ssl',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_PORT',
            'fieldValue' => '465',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_AUTHENTICATION',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_SENDER_NAME',
            'fieldValue' => 'Admin',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_SENDER_EMAIL',
            'fieldValue' => MyLibrary::getEncryptedString('netquick@websiteinquiries.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_META_TITLE',
            'fieldValue' => "NetQuick",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_META_DESCRIPTION',
            'fieldValue' => "NetQuick",
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'GOOGLE_ANALYTIC_CODE',
            'fieldValue' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'GOOGLE_TAG_MANAGER_FOR_BODY',
            'fieldValue' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_FB_LINK',
            'fieldValue' => 'http://www.fb.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'Google_Plus_Link',
            'fieldValue' => 'https://www.google.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_TWITTER_LINK',
            'fieldValue' => 'http://www.twitter.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_YOUTUBE_LINK',
            'fieldValue' => 'https://www.youtube.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_TRIPADVISOR_LINK',
            'fieldValue' => 'http://www.tripadvisor.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_LINKEDIN_LINK',
            'fieldValue' => 'https://www.linkedin.com/',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_INSTAGRAM_LINK',
            'fieldValue' => 'http://www.instagram.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_TUMBLR_LINK',
            'fieldValue' => 'http://www.tumblr.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_PINTEREST_LINK',
            'fieldValue' => 'http://www.pinterest.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_FLICKR_LINK',
            'fieldValue' => 'http://www.flickr.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_DRIBBBLE_LINK',
            'fieldValue' => 'http://www.dribbble.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_RSS_FEED_LINK',
            'fieldValue' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_TIME_ZONE',
            'fieldValue' => 'America/Cayman',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_DATE_FORMAT',
            'fieldValue' => 'M/d/Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_TIME_FORMAT',
            'fieldValue' => 'g:i A',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_PAGE_SIZE',
            'fieldValue' => '20',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'GOOGLE_MAP_KEY',
            'fieldValue' => 'AIzaSyBuP6iYSp6RJ3cRvylR_68ibx4wTrPCXdw',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'GOOGLE_CAPCHA_KEY',
            'fieldValue' => '6LcKXMgUAAAAAKzb_F08TaQC8SOMIUZ51s21Eflr',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'GOOGLE_CAPCHA_SECRET',
            'fieldValue' => 'AIzaSyBuP6iYSp6RJ3cRvylR',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'GOOGLE_CAPTCHA_SECRET',
            'fieldValue' => '6LcKXMgUAAAAAPKzoF18ryq_tOxoBY8bRHlc9TaL',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'BAD_WORDS',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'AVAILABLE_SOCIAL_LINKS_FOR_TEAM_MEMBER',
            'fieldValue' => serialize(["0" => ["title" => "Facebook", "placeholder" => "http://www.facebook.com", "class" => "fa fa-facebook", "key" => "social_0"]]),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'BING_FILE_PATH',
            'fieldValue' => null,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'FOOTER_COPYRIGHTS',
            'fieldValue' => 'Copyright &copy;',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'FOOTER_YEAR',
            'fieldValue' => '2017',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'FOOTER_DEVELOPED_BY',
            'fieldValue' => 'Website Designed &amp; Developed By: <a href="http://www.netclues.com" target="_blank" rel="nofollow" title="Netclues"><span class="netclues_logo"></span></a>',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_CURRENCY_SYMBOL',
            'fieldValue' => '$',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'CURRENCY',
            'fieldValue' => 'KYD',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_FB_ID',
            'fieldValue' => 1234567890,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_FB_API_KEY',
            'fieldValue' => '12sd3456ds7890ds',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_FB_SECRET_KEY',
            'fieldValue' => '12f3s45t67y890oo',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_FB_ACCESS_TOKEN',
            'fieldValue' => '12f3s45t67y890oo',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_TWITTER_API_KEY',
            'fieldValue' => '12f3s45t67y890sds',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_TWITTER_SECRET_KEY',
            'fieldValue' => '12f3s45t67y890rer',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_TWITTER_ACCESS_TOKEN',
            'fieldValue' => '12f3s45t67y890eruhsd',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_TWITTER_ACCESS_SECRET_KEY',
            'fieldValue' => 'rer12f3s45t67y890eruhsd',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_LINKEDIN_API_KEY',
            'fieldValue' => '12f3s45t67y890dssds',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_LINKEDIN_SECRET_KEY',
            'fieldValue' => 'dffdf474ynjnj',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_LINKEDIN_ACCESS_TOKEN',
            'fieldValue' => '12f3s45t67y8hsd',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_LINKEDIN_ACCESS_SECRET_KEY',
            'fieldValue' => '2f3s45t67y890eru',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}
