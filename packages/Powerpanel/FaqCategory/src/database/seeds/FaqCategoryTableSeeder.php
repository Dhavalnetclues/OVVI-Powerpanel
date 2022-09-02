<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class FaqCategoryTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Faq Category')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Faq Category',
                    'varModuleName' => 'faq-category',
                    'varTableName' => 'faq_category',
                    'varModelName' => 'FaqCategory',
                    'varModuleClass' => 'FaqCategoryController',
                    'varModuleNameSpace' => 'Powerpanel\FaqCategory\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            } else {
                DB::table('module')->insert([
                    'varTitle' => 'Faq Category',
                    'varModuleName' => 'faq-category',
                    'varTableName' => 'faq_category',
                    'varModelName' => 'FaqCategory',
                    'varModuleClass' => 'FaqCategoryController',
                    'varModuleNameSpace' => 'Powerpanel\FaqCategory\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'Faq Category')->first();
            $permissions = [];
            foreach (explode(',', $pageModuleCode->varPermissions) as $permissionName) {
                $permissionName = trim($permissionName);
                $Icon = $permissionName;

                if ($permissionName == 'list') {
                    $Icon = 'per_list';
                } elseif ($permissionName == 'create') {
                    $Icon = 'per_add';
                } elseif ($permissionName == 'edit') {
                    $Icon = 'per_edit';
                } elseif ($permissionName == 'delete') {
                    $Icon = 'per_delete';
                } elseif ($permissionName == 'publish') {
                    $Icon = 'per_publish';
                } elseif ($permissionName == 'reviewchanges') {
                    $Icon = 'per_reviewchanges';
                }
                array_push($permissions, [
                    'name' => $pageModuleCode->varModuleName . '-' . $permissionName,
                    'display_name' => $Icon,
                    'description' => ucwords($permissionName) . ' Permission',
                    'intFKModuleCode' => $pageModuleCode->id
                ]);
            }

            foreach ($permissions as $key => $value) {
                $id = DB::table('permissions')->insertGetId($value);
                for ($roleId = 1; $roleId <= 3; $roleId++) {
                    $value = [
                        'permission_id' => $id,
                        'role_id' => $roleId,
                    ];
                    DB::table('permission_role')->insert($value);
                }
            }
        }

        $pageModuleCode = DB::table('module')->where('varTitle', 'Faq Category')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Faqs'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Faq Category'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Faq Category')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Faq Category',
                    'varMetaKeyword' => 'Faq Category',
                    'varMetaDescription' => 'Faq Category',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Faq Category'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Faq Category')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Faq Category',
                    'varMetaKeyword' => 'Faq Category',
                    'varMetaDescription' => 'Faq Category',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        
        DB::table('faq_category')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Faq Category 1')), $intFKModuleCode),
            'varTitle' => 'Faq Category 1',
            'txtDescription' => '',
            'varMetaTitle' => 'Faq Category 1',
            'varMetaKeyword' => 'Faq Category 1',
            'varMetaDescription' => 'Faq Category 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('faq_category')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Faq Category 2')), $intFKModuleCode),
            'varTitle' => 'Faq Category 2',
            'txtDescription' => '',
            'varMetaTitle' => 'Faq Category 2',
            'varMetaKeyword' => 'Faq Category 2',
            'varMetaDescription' => 'Faq Category 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('faq_category')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Faq Category 3')), $intFKModuleCode),
            'varTitle' => 'Faq Category 3',
            'txtDescription' => '',
            'varMetaTitle' => 'Faq Category 3',
            'varMetaKeyword' => 'Faq Category 3',
            'varMetaDescription' => 'Faq Category 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        
        
        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Faqs')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'faqcategory')->first();
        
        if (Schema::hasTable('visualcomposer')) {
            //Adding Faq Module In visual composer
            $FaqModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Faqs')->where('fkParentID', '0')->first();

            if (!isset($FaqModule->id) || empty($FaqModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Faqs',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $FaqModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Faqs')->where('fkParentID', '0')->first();

            $faqChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Faqs')->where('fkParentID', '<>', '0')->first();

            if (!isset($faqChild->id) || empty($faqChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $FaqModule->id,
                    'varTitle' => 'Faqs',
                    'varIcon' => 'fa fa-question-circle-o',
                    'varClass' => 'faqs',
                    'varTemplateName' => 'faq-category::partial.faqs',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
            
            $AllFaq = DB::table('visualcomposer')->select('id')->where('varTitle','All Faqs')->where('fkParentID','0')->first();
            
            if(!isset($AllFaq->id) || empty($AllFaq->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $FaqModule->id,
                    'varTitle' => 'All Faqs',
                    'varIcon' =>  'fa fa-question-circle-o',
                    'varClass' => 'faqs-template',
                    'varTemplateName' => 'faq-category::partial.all-faqs',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at'=> Carbon::now(),
                    'updated_at'=> Carbon::now()
                ]);
            }
        }
    }

}
