<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BannerTableSeeder extends Seeder
{
    public function run()
    {

        $fkIntPageId = DB::table('cms_page')->select('id')->where('varTitle', 'Home')->first();
        // $fkIntModuleId = DB::table('module')->select('id')->where('varModuleName', 'home')->first();

        $exists = DB::table('banner')->select('id')->where('varTitle', 'Special Restaurant')->first();
        if (!isset($exists->id)) {
            $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'banner_02_01')->first();
            DB::table('banner')->insert([
                //  'fkIntPageId'      => $fkIntPageId->id,
                 'fkIntImgId' => $fkIntImgId->id,
                //  'fkModuleId'       => $fkIntModuleId->id,
                 // 'varBannerType'    => 'home_banner',
                 'varTitle' => 'Special Restaurant',
                'varSubTitle' => "Expand",
                'intDisplayOrder' => 1,
                'txtDescription' => "Special Restaurant &lt;p&gt; Sorem Ipsum is simply dummy text of the printing industry! &lt;br/&gt;Aorem Ipsum industry&#039;s standard dummy 1500s, &lt;/p&gt;",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                // 'chrDefaultBanner' => 'N',
                 'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $fkIntPageId = DB::table('cms_page')->select('id')->where('varTitle', 'Home')->first();
        //  $fkIntModuleId = DB::table('module')->select('id')->where('varModuleName', 'home')->first();

        $exists = DB::table('banner')->select('id')->where('varTitle', 'Special Restaurant')->first();
        if (!isset($exists->id)) {
            $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'banner_02_02')->first();
            DB::table('banner')->insert([
                // 'fkIntPageId'      => $fkIntPageId->id,
                 'fkIntImgId' => $fkIntImgId->id,
                // 'fkModuleId'       => $fkIntModuleId->id,
                 // 'varBannerType'    => 'home_banner',
                 'varTitle' => 'Special Restaurant',
                'varSubTitle' => "Delicious Food!",
                'intDisplayOrder' => 2,
                'txtDescription' => "Special Restaurant &lt;p&gt; Sorem Ipsum is simply dummy text of the printing industry! &lt;br/&gt;Aorem Ipsum industry&#039;s standard dummy 1500s, &lt;/p&gt;",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                // 'chrDefaultBanner' => 'N',
                 'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

        $fkIntPageId = DB::table('module')->select('id')->where('varModuleName', '=', 'blogs')->first();
        if (!isset($fkIntPageId->id)) {
            $fkIntPageId = DB::table('module')->select('id')->where('varModuleName', 'like', '%blogs%')->first();
        }
        // $fkIntModuleId = DB::table('cms_page')->select('id')->where('intFKModuleCode', $fkIntPageId->id)->first();

        $exists = DB::table('banner')->select('id')->where('varTitle', 'Main Title')->first();
        if (!isset($exists->id)) {
            $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'inner_banner_01_01')->first();
            DB::table('banner')->insert([
                // 'fkIntPageId'      => $fkIntPageId->id,
                 'fkIntImgId' => $fkIntImgId->id,
                // 'fkModuleId'       => $fkIntModuleId->id,
                 //'varBannerType'    => 'inner_banner',
                 'varTitle' => 'Main Title',
                'varSubTitle' => "",
                'intDisplayOrder' => 3,
                'txtDescription' => "Small Text Title Here",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                // 'chrDefaultBanner' => 'Y',
                 'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        }

    }
}
