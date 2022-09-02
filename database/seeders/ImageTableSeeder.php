<?php
namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ImageTableSeeder extends Seeder
{
    public function run()
    {

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'logo',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'logo',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'footer_logo',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'footer_logo',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_banner_01_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_banner_01_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_banner_01_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_banner_01_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_banner_01_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_banner_01_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_bg',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_bg',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_07_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_07_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_07_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_07_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_15_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_15_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_15_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_15_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_15_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_15_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_15_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_15_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_15_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_15_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_15_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_15_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_15_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_15_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_15_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_15_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_16_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_16_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_16_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_16_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_16_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_16_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_16_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_16_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_16_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_16_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_16_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_16_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_16_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_16_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_16_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_16_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_07_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_07_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_07_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_07_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'captcha-img',
            'varImageExtension' => 'gif',
            'txtImgOriginalName' => 'captcha-img',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'cms',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'cms',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'li',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'li',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_18_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_18_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_18_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_18_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_18_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_18_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_18_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_18_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_18_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_18_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_18_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_18_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_18_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_18_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_18_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_18_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_18_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_17_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_17_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_17_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_17_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_17_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_17_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_17_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_17_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_17_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_17_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_17_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_17_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_17_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_17_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_17_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_17_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_17_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_signin_01_bg',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_signin_01_bg',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_signin_01_bg',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_signin_01_bg',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_signup_01_bg',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_signup_01_bg',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_signin_01_bg',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_signin_01_bg',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_02_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_11_icon_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_11_icon_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_11_icon_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_10_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_10_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_10_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_09_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_09_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_13_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_inner_banner_01_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'webp',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_04_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_04_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_07_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_07_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'ttheme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'ttheme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_08_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_08_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_12_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'theme_01_section_12_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'theme_01_section_14_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'theme_01_section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

#=
        #==

    }
}
