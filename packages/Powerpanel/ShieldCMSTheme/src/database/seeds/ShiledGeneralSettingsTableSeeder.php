<?php
use App\Helpers\MyLibrary;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ShiledGeneralSettingsTableSeeder extends Seeder
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
            'fieldName' => 'BAD_WORDS',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SITE_NAME',
            'fieldValue' => 'Shield CMS',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_TIME_FORMAT',
            'fieldValue' => 'h:i A',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'FRONT_LOGO_ID',
            'fieldValue' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_PAGE_SIZE',
            'fieldValue' => '9',
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
            'fieldName' => 'DEFAULT_TIME_ZONE',
            'fieldValue' => 'America/Cayman',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_EMAIL',
            'fieldValue' => MyLibrary::getLaravelEncryptedString('demo1.netclues@gmail.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_PHONENO',
            'fieldValue' => '(242) 302 2600',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_ADDRESS',
            'fieldValue' => 'Shield CMS\r\n',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'Maintenancenew_Hour',
            'fieldValue' => '02:00',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'paymenttype',
            'fieldValue' => 'M',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_NEWSLETTER_EMAIL',
            'fieldValue' => MyLibrary::getLaravelEncryptedString('demo1.netclues@gmail.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_REPLYTO_EMAIL',
            'fieldValue' => '2020-06-18 13:35:57',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_CONTACTUS_EMAIL',
            'fieldValue' => MyLibrary::getLaravelEncryptedString('demo1.netclues@gmail.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_FEEDBACKFORM',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'extebdmonth',
            'fieldValue' => 'N',
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
            'fieldName' => 'MAILER',
            'fieldValue' => 'log',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_SERVER',
            'fieldValue' => 'smtp-relay.sendinblue.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_USERNAME',
            'fieldValue' => 'testbynetclues@gmail.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_PASSWORD',
            'fieldValue' => 'ZmgJfjGxaT10PVyI',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_ENCRYPTION',
            'fieldValue' => 'tls',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SMTP_PORT',
            'fieldValue' => '587',
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
            'fieldValue' => MyLibrary::getLaravelEncryptedString('netquick@websiteinquiries.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_SIGNATURE_CONTENT',
            'fieldValue' => NULL,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'USE_SMTP_SETTING',
            'fieldValue' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_DRAFT',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_TRASH',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_QUICK',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_DUPLICATE',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_VISIBILITY',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'GOOGLE_ANALYTIC_CODE',
            'fieldValue' => NULL,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'GOOGLE_TAG_MANAGER_FOR_BODY',
            'fieldValue' => NULL,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_META_TITLE',
            'fieldValue' => 'Goverment Portal CMS System',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_META_KEYWORD',
            'fieldValue' => 'Goverment Portal CMS System',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_META_DESCRIPTION',
            'fieldValue' => 'Goverment Portal CMS System',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_FB_LINK',
            'fieldValue' => 'https://www.facebook.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'Google_Plus_Link',
            'fieldValue' => '2020-06-12 04:49:15',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_TWITTER_LINK',
            'fieldValue' => 'https://www.twitter.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_YOUTUBE_LINK',
            'fieldValue' => 'http://www.youtube.com',
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
            'fieldName' => 'SOCIAL_TWITTER_LINK2',
            'fieldValue' => '2020-06-12 04:49:15',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_SHARE_FB_ID',
            'fieldValue' => '1234567890',
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

        DB::table('general_setting')->insert([
            'fieldName' => 'GOOGLE_CAPCHA_KEY',
            'fieldValue' => '6LdIFCUUAAAAAGe8wd_az7YQvLii27ou77_DjBGr',
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
            'fieldName' => 'GOOGLE_CAPCHA_SECRET',
            'fieldValue' => 'AIzaSyBuP6iYSp6RJ3cRvylR',
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
            'fieldValue' => 'Powered By: <a href=\"https://www.netclues.com\" target=\"_blank\" rel=\"nofollow\" title=\"Netclues\"><span class=\"netclues_logo\"></span></a>',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'AVAILABLE_SOCIAL_LINKS_FOR_TEAM_MEMBER',
            'fieldValue' => 'a:0:{}',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'BING_FILE_PATH',
            'fieldValue' => NULL,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_INSTAGRAM_LINK',
            'fieldValue' => 'https://www.instagram.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_INSTAGRAM_LINK',
            'fieldValue' => 'https://www.instagram.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_TUMBLR_LINK',
            'fieldValue' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_PINTEREST_LINK',
            'fieldValue' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_FLICKR_LINK',
            'fieldValue' => '',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_DRIBBBLE_LINK',
            'fieldValue' => '',
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
            'fieldName' => 'DEFAULT_FEEDBACK_EMAIL',
            'fieldValue' => MyLibrary::getLaravelEncryptedString('demo1.netclues@gmail.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SUBMIT_TICKET',
            'fieldValue' => MyLibrary::getLaravelEncryptedString('demo1.netclues@gmail.com'),
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_NOTIFCATION_DEPARTMENT_EMAIL',
            'fieldValue' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_VISUAL',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SOCIAL_TRIP_ADVISOR_LINK',
            'fieldValue' => 'https://www.tripadvisor.in',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'LOG_REMOVE_TIME',
            'fieldValue' => '6',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'MAX_LOGIN_ATTEMPTS',
            'fieldValue' => '5',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'RETRY_TIME_PERIOD',
            'fieldValue' => '5',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'LOCKOUT_TIME',
            'fieldValue' => '30',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'IP_SETTING',
            'fieldValue' => '192.168.1.132,192.168.1.218,192.168.1.220,192.168.1.221,192.168.1.203,103.226.187.41,27.54.170.98,192.168.1.219,192.168.1.133,192.168.1.216',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_DAYS',
            'fieldValue' => '2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_Authentication',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_Authentication_TIME',
            'fieldValue' => '5',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_FAVORITE',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_AUTHENTICATION',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'Magic_Send_Email',
            'fieldValue' => 'sppatel@netclues.com,npadliya@netclues.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'Magic_Auth_Password',
            'fieldValue' => '123456789@#$%^&*2345',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'Magic_Receive_Email',
            'fieldValue' => 'v5.netclues@gmail.com',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'Magic_Receive_Password',
            'fieldValue' => 'Netclues#786',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'SITE_PATH',
            'fieldValue' => 'http://localhost/netquick-powerpanel/public_html/',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'Maintenancenew_Rep_Send_Email',
            'fieldValue' => 'sadsadasd',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_ONLINEPOLLINGFORM',
            'fieldValue' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_SHARINGOPTION',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_EMAILTOFRIENDOPTION',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_ARCHIVE',
            'fieldValue' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_FORMBUILDER',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_PAGETEMPLATE',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_SPELLCHCEK',
            'fieldValue' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_MESSAGINGSYSTEM',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_CONTENTLOCK',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'DEFAULT_AUDIO',
            'fieldValue' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('general_setting')->insert([
            'fieldName' => 'PUBLISH_CONTENT_MODULE',
            'fieldValue' => NULL,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        
    }
}