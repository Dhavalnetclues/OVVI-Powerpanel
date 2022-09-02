<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TestimonialTableSeeder extends Seeder
{
    public function run()
    {
        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'testimonials')->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_01')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",
            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'txtShortDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_02')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",
            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'txtShortDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_03')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",
            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'txtShortDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_04')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",
            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'txtShortDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'testimonials')->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_01')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_02')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_03')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_04')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_01')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_02')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_03')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_04')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_01')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_02')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_03')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_07_img_04')->first();

        $insetId = DB::table('testimonials')->insertGetId([

            'varTitle' => "Customer Name Here",

            'fkIntImgId' => $fkIntImgId->id,

            'txtDescription' => "&lt;p&gt;Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse.&lt;/p&gt;",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image_module_rel')->insert([
            'intFkImgId' => $fkIntImgId->id,
            'intFkModuleCode' => $moduleCode->id,
            'intRecordId' => $insetId,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        #=
        #==
    }
}
