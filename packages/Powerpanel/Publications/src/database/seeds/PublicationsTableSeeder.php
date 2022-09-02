<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class PublicationsTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Publications')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Publications',
                    'varModuleName' => 'publications',
                    'varTableName' => 'publications',
                    'varModelName' => 'Publications',
                    'varModuleClass' => 'PublicationsController',
                    'varModuleNameSpace' => 'Powerpanel\Publications\\',
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
                    'varTitle' => 'Publications',
                    'varModuleName' => 'publications',
                    'varTableName' => 'publications',
                    'varModelName' => 'Publications',
                    'varModuleClass' => 'PublicationsController',
                    'varModuleNameSpace' => 'Powerpanel\Publications\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Publications')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Publications Category')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Publications'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Publications'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Publications')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Publications',
                    'varMetaKeyword' => 'Publications',
                    'varMetaDescription' => 'Publications',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Publications'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Publications')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Publications',
                    'varMetaKeyword' => 'Publications',
                    'varMetaDescription' => 'Publications',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Publications')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'publications')->first();
        
         $pageModuleCodealias = DB::table('module')->select('id')->where('varTitle', 'Publications')->first();
        $intFKModuleCodealias = $pageModuleCodealias->id;
        
        DB::table('publications')->insert([
            'varTitle' => 'Publications 1',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Publications 1')), $intFKModuleCodealias),
             'txtCategories' => 1,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Publications 1',
            'varMetaKeyword' => 'Publications 1',
            'varMetaDescription' => 'Publications 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('publications')->insert([
            'varTitle' => 'Publications 2',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Publications 2')), $intFKModuleCodealias),
             'txtCategories' => 2,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Publications 2',
            'varMetaKeyword' => 'Publications 2',
            'varMetaDescription' => 'Publications 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('publications')->insert([
            'varTitle' => 'Publications 3',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Publications 3')), $intFKModuleCodealias),
             'txtCategories' => 3,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Publications 3',
            'varMetaKeyword' => 'Publications 3',
            'varMetaDescription' => 'Publications 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if (Schema::hasTable('visualcomposer')) {
            //Adding Publications Module In visual composer
            $publicationsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Publications')->where('fkParentID', '0')->first();

            if (!isset($publicationsModule->id) || empty($publicationsModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Publications',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $publicationsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Publications')->where('fkParentID', '0')->first();

            $publicationsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Publications')->where('fkParentID', '<>', '0')->first();

            if (!isset($publicationsChild->id) || empty($publicationsChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $publicationsModule->id,
                    'varTitle' => 'Publications',
                    'varIcon' => 'fa fa-book',
                    'varClass' => 'publication',
                    'varTemplateName' => 'publications::partial.publications',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $latestPublication = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Publications')->where('fkParentID', '0')->first();

            if (!isset($latestPublication->id) || empty($latestPublication->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $publicationsModule->id,
                    'varTitle' => 'All Publications',
                    'varIcon' => 'fa fa-book',
                    'varClass' => 'publication-template',
                    'varTemplateName' => 'publications::partial.all-publications',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }

}
