<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ProductsTableSeeder extends Seeder
{
    public function run()
    {
        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'products')->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_01')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '1')->orWhere('varTitle', 'Product One')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product One",
                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product One'), $moduleCode->id),
                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '1',
                'txtDescription' => "",
                'txtShortDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product One",
                'varMetaKeyword' => "Product One",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_02')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '2')->orWhere('varTitle', 'Product Two')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Two",
                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Two'), $moduleCode->id),
                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '2',
                'txtDescription' => "",
                'txtShortDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Two",
                'varMetaKeyword' => "Product Two",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_03')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '3')->orWhere('varTitle', 'Product Three')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Three",
                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Three'), $moduleCode->id),
                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '3',
                'txtDescription' => "",
                'txtShortDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Three",
                'varMetaKeyword' => "Product Three",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_04')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '4')->orWhere('varTitle', 'Product Four')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Four",
                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Four'), $moduleCode->id),
                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '4',
                'txtDescription' => "",
                'txtShortDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Four",
                'varMetaKeyword' => "Product Four",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_01')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '5')->orWhere('varTitle', 'Product Five')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Five",
                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Five'), $moduleCode->id),
                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '5',
                'txtDescription' => "",
                'txtShortDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Five",
                'varMetaKeyword' => "Product Five",
                'varMetaDescription' => "",
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

        }

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'products')->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_01')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '1')->orWhere('varTitle', 'Product One')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product One",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product One'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '1',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product One",
                'varMetaKeyword' => "Product One",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_02')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '2')->orWhere('varTitle', 'Product Two')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Two",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Two'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '2',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Two",
                'varMetaKeyword' => "Product Two",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_03')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '3')->orWhere('varTitle', 'Product Three')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Three",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Three'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '3',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Three",
                'varMetaKeyword' => "Product Three",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_04')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '4')->orWhere('varTitle', 'Product Four')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Four",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Four'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '4',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Four",
                'varMetaKeyword' => "Product Four",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_01')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '5')->orWhere('varTitle', 'Product Five')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Five",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Five'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '5',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Five",
                'varMetaKeyword' => "Product Five",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_02')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '6')->orWhere('varTitle', 'Product Six')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Six",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Six'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '6',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Six",
                'varMetaKeyword' => "Product Six",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_03')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '7')->orWhere('varTitle', 'Product Seven')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Seven",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Seven'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '7',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Seven",
                'varMetaKeyword' => "Product Seven",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_04')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '8')->orWhere('varTitle', 'Product Eight')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Eight",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Eight'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '8',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Eight",
                'varMetaKeyword' => "Product Eight",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'ttheme_01_section_12_img_01')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '9')->orWhere('varTitle', 'Product Nine')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Nine",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Nine'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '9',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Nine",
                'varMetaKeyword' => "Product Nine",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_02')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '10')->orWhere('varTitle', 'Product Ten')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Ten",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Ten'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '10',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Ten",
                'varMetaKeyword' => "Product Ten",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_03')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '11')->orWhere('varTitle', 'Product Eleven')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Eleven",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Eleven'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '11',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Eleven",
                'varMetaKeyword' => "Product Eleven",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_04')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '12')->orWhere('varTitle', 'Product Twelve')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "Product Twelve",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Twelve'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '12',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "Product Twelve",
                'varMetaKeyword' => "Product Twelve",
                'varMetaDescription' => "",
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

        }

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'products')->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_01')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '1')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '1',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_02')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '2')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '2',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_03')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '3')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '3',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_04')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '4')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '4',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_01')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '5')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '5',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_02')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '6')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '6',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_03')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '7')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '7',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_04')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '8')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '8',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_01')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '9')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '9',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_02')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '10')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '10',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_03')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '11')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '11',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_12_img_04')->first();

        $recId = DB::table('products')->select('id')->where('intDisplayOrder', '12')->orWhere('varTitle', 'PRODUCTS NAME HERE')->first();
        if (!isset($recId->id)) {

            $insetId = DB::table('products')->insertGetId([

                'varTitle' => "PRODUCTS NAME HERE",

                'intAliasId' => MyLibrary::insertAlias(slug::create_slug('PRODUCTS NAME HERE'), $moduleCode->id),

                'fkIntImgId' => $fkIntImgId->id,

                'intDisplayOrder' => '12',

                'txtDescription' => "",
                'chrPublish' => 'Y',
                'chrDelete' => 'N',
                'varMetaTitle' => "PRODUCTS NAME HERE",
                'varMetaKeyword' => "PRODUCTS NAME HERE",
                'varMetaDescription' => "",
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

        }

        #=
        #==
    }
}
