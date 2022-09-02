<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use DB;

class ConsultationsTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Consultations')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Consultations',
                    'varModuleName' => 'consultations',
                    'varTableName' => 'Consultations',
                    'varModelName' => 'Consultations',
                    'varModuleClass' => 'ConsultationsController',
                    'varModuleNameSpace' => 'Powerpanel\Consultations\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('module')->insert([
                    'varTitle' => 'Consultations',
                    'varModuleName' => 'consultations',
                    'varTableName' => 'Consultations',
                    'varModelName' => 'Consultations',
                    'varModuleClass' => 'ConsultationsController',
                    'varModuleNameSpace' => 'Powerpanel\Consultations\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'Consultations')->first();
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
                    'intFKModuleCode' => $pageModuleCode->id,
                ]);
            }

            foreach ($permissions as $key => $value) {
                $id = DB::table('permissions')->insertGetId($value);
                for ($roleId = 1; $roleId <= 3; $roleId++) {
                    $value = [
                        'permission_id' => $id,
                        'role_id' => $roleId,
                    ];
                    DB::table('role_has_permissions')->insert($value);
                }
            }
        }

        $pageModuleCode = DB::table('module')->where('varTitle', 'Consultations')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Consultations'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Consultations'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Consultations')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Consultations',
                    'varMetaKeyword' => 'Consultations',
                    'varMetaDescription' => 'Consultations',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Consultations'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Consultations')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Consultations',
                    'varMetaKeyword' => 'Consultations',
                    'varMetaDescription' => 'Consultations',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Consultations')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'consultations')->first();

        $pageModuleCodealias = DB::table('module')->select('id')->where('varTitle', 'Consultations')->first();
        $intFKModuleCodealias = $pageModuleCodealias->id;

        DB::table('consultations')->insert([
            'varSector' => 'ofreg',
            'varTitle' => 'consulations 1',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Consultations 1')), $intFKModuleCodealias),
            'txtCategories' => 'consultations',
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'intDisplayOrder' => '1',
            'varMetaTitle' => 'Consultations 1',
            'varMetaKeyword' => 'Consultations 1',
            'varMetaDescription' => 'Consultations 1',
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('consultations')->insert([
            'varSector' => 'water',
            'varTitle' => 'consultations 2',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('consultations 2')), $intFKModuleCodealias),
            'txtCategories' => 'determinations',
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'consultations 2',
            'varMetaKeyword' => 'consultations 2',
            'varMetaDescription' => 'consultations 2',
            'intDisplayOrder' => '2',
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('consultations')->insert([
            'varSector' => 'ict',
            'varTitle' => 'consultations 3',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('consultations 3')), $intFKModuleCodealias),
            'txtCategories' => 'completed_consultations',
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'consultations 3',
            'varMetaKeyword' => 'consultations 3',
            'varMetaDescription' => 'consultations 3',
            'intDisplayOrder' => '3',
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // if (Schema::hasTable('visualcomposer')) {
        //     //Adding News Module In visual composer
        //     $newsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'News')->where('fkParentID', '0')->first();

        //     if (!isset($newsModule->id) || empty($newsModule->id)) {
        //         DB::table('visualcomposer')->insert([
        //             'fkParentID' => '0',
        //             'varTitle' => 'News',
        //             'varIcon' => '',
        //             'varClass' => '',
        //             'varTemplateName' => '',
        //             'varModuleID' => $pageModuleCode->id,
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]);
        //     }

        //     $newsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'News')->where('fkParentID', '0')->first();

        //     $newsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'News')->where('fkParentID', '<>', '0')->first();

        //     if (!isset($newsChild->id) || empty($newsChild->id)) {
        //         DB::table('visualcomposer')->insert([
        //             'fkParentID' => $newsModule->id,
        //             'varTitle' => 'News',
        //             'varIcon' => 'fa fa-newspaper-o',
        //             'varClass' => 'news',
        //             'varTemplateName' => 'news::partial.news',
        //             'varModuleID' => $pageModuleCode->id,
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]);
        //     }

        //     $latestNews = DB::table('visualcomposer')->select('id')->where('varTitle', 'All News')->where('fkParentID', '0')->first();

        //     if (!isset($latestNews->id) || empty($latestNews->id)) {
        //         DB::table('visualcomposer')->insert([
        //             'fkParentID' => $newsModule->id,
        //             'varTitle' => 'All News',
        //             'varIcon' => 'fa fa-newspaper-o',
        //             'varClass' => 'news-template',
        //             'varTemplateName' => 'news::partial.all-news',
        //             'varModuleID' => $pageModuleCode->id,
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]);
        //     }
        // }
    }

}
