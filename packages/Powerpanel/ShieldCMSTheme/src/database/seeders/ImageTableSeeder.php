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
            'txtImageName' => 'banner_02_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'banner_02_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'banner_02_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'banner_02_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'inner_banner_01_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'inner_banner_01_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_02_img',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_02_img',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_11_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_11_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_11_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_11_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_11_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_11_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_11_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_11_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_13_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_13_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_13_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_13_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_13_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_13_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_13_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_13_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_13_img_05',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_13_img_05',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_13_img_06',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_13_img_06',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_14_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_14_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_14_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_14_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_14_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_14_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_14_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_14_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_15_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'section_15_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_15_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'section_15_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_15_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'section_15_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_15_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'section_15_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_15_img_05',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'section_15_img_05',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_17_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_17_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_17_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_17_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_17_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'section_17_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_18_svg_01',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'section_18_svg_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_18_svg_02',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'section_18_svg_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_18_svg_03',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'section_18_svg_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_18_svg_04',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'section_18_svg_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'blog_01_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'blog_01_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'blog_01_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'blog_01_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'blog_01_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'blog_01_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'blog_01_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'blog_01_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'blog_01_img_05',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'blog_01_img_05',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'blog_01_img_06',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'blog_01_img_06',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'blog_01_img_07',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'blog_01_img_07',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'blog_01_img_08',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'blog_01_img_08',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'blog_01_img_09',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'blog_01_img_09',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_05',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_05',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_06',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_06',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_07',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_07',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_08',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_08',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_09',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_09',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_10',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_10',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_11',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_11',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'photogallery_01_img_12',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'photogallery_01_img_12',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_05',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_05',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_06',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_06',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_07',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_07',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_08',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_08',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_09',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_09',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_10',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_10',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_11',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_11',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'team_02_img_12',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'team_02_img_12',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'testimonials_02_img_01',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'testimonials_02_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'testimonials_02_img_02',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'testimonials_02_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'testimonials_02_img_03',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'testimonials_02_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'testimonials_02_img_04',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'testimonials_02_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'testimonials_02_img_05',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'testimonials_02_img_05',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'testimonials_02_img_06',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'testimonials_02_img_06',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'testimonials_02_img_07',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'testimonials_02_img_07',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'testimonials_02_img_08',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'testimonials_02_img_08',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'testimonials_02_img_09',
            'varImageExtension' => 'jpg',
            'txtImgOriginalName' => 'testimonials_02_img_09',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'service_02_svg_01',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'service_02_svg_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'service_02_svg_02',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'service_02_svg_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'service_02_svg_03',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'service_02_svg_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_18_svg_04',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'section_18_svg_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'service_02_svg_01',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'service_02_svg_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'service_02_svg_02',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'service_02_svg_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'service_02_svg_03',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'service_02_svg_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_18_svg_04',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'section_18_svg_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'service_02_svg_01',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'service_02_svg_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'service_02_svg_02',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'service_02_svg_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'service_02_svg_03',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'service_02_svg_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'section_18_svg_04',
            'varImageExtension' => 'svg',
            'txtImgOriginalName' => 'section_18_svg_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'menu_01_img_01',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'menu_01_img_01',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'menu_01_img_02',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'menu_01_img_02',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'menu_01_img_03',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'menu_01_img_03',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'menu_01_img_04',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'menu_01_img_04',
            'chrIsUserUploaded' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('image')->insert([
            'fkIntUserId' => 1,
            'txtImageName' => 'menu_01_img_05',
            'varImageExtension' => 'png',
            'txtImgOriginalName' => 'menu_01_img_05',
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
