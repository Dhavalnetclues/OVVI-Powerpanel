<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use DB;
use Illuminate\Database\Schema\Blueprint;

class InterconnectionsTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Interconnections')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Interconnections',
                    'varModuleName' => 'interconnections',
                    'varTableName' => 'interconnections',
                    'varModelName' => 'Interconnections',
                    'varModuleClass' => 'InterconnectionsController',
                    'varModuleNameSpace' => 'Powerpanel\Interconnections\\',
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
                    'varTitle' => 'Interconnections',
                    'varModuleName' => 'interconnections',
                    'varTableName' => 'interconnections',
                    'varModelName' => 'Interconnections',
                    'varModuleClass' => 'InterconnectionsController',
                    'varModuleNameSpace' => 'Powerpanel\Interconnections\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Interconnections')->first();
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
                    DB::table('role_has_permissions')->insert($value);
                }
            }
        }


        $pageModuleCode = DB::table('module')->where('varTitle', 'Interconnections')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Interconnections'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Interconnections'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Interconnections')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Interconnections',
                    'varMetaKeyword' => 'Interconnections',
                    'varMetaDescription' => 'Interconnections',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Interconnections'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Interconnections')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Interconnections',
                    'varMetaKeyword' => 'Interconnections',
                    'varMetaDescription' => 'Interconnections',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
        // DB::table('interconnections')->insert([
        //     'varTitle' => 'Interconnections 1',
        //     'intParentCategoryId' => 0,
        //     'varSector' => 'ofreg',
        //     'txtShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
        //     'chrMain' => 'Y',
        //     'intDisplayOrder' => '1', 
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);

        //  DB::table('interconnections')->insert([
        //     'varTitle' => 'Interconnections 2',
        //     'varSector' => 'water',
        //     'intParentCategoryId' => 0,
        //     'txtShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
        //     'chrMain' => 'Y',
        //     'intDisplayOrder' => '2', 
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);
        
        //  DB::table('interconnections')->insert([
        //     'varTitle' => 'Interconnections 3',
        //     'varSector' => 'ict',
        //     'intParentCategoryId' => 0,
        //     'txtShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
        //     'chrMain' => 'Y',
        //     'intDisplayOrder' => '3', 
        //     'created_at' => Carbon::now(),
        //     'updated_at' => Carbon::now()
        // ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Interconnections')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'Interconnections')->first();
        
        if (Schema::hasTable('visualcomposer')) {
            //Adding Interconnections Module In visual composer
            $interconnectionsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Interconnections')->where('fkParentID', '0')->first();

            if (!isset($interconnectionsModule->id) || empty($interconnectionsModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Interconnections',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $interconnectionsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Interconnections')->where('fkParentID', '0')->first();

            $InterconnectionsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Interconnections')->where('fkParentID', '<>', '0')->first();

            if (!isset($InterconnectionsChild->id) || empty($InterconnectionsChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $interconnectionsModule->id,
                    'varTitle' => 'All Interconnections',
                    'varIcon' => 'fa fa-university',
                    'varClass' => 'interconnections',
                    'varTemplateName' => 'Interconnections::partial.all-interconnections',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }

}
