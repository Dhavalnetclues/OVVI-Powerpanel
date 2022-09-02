<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class OrganizationsTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Organizations')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Organizations',
                    'varModuleName' => 'organizations',
                    'varTableName' => 'organizations',
                    'varModelName' => 'Organizations',
                    'varModuleClass' => 'OrganizationsController',
                    'varModuleNameSpace' => 'Powerpanel\Organizations\\',
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
                    'varTitle' => 'Organizations',
                    'varModuleName' => 'organizations',
                    'varTableName' => 'organizations',
                    'varModelName' => 'Organizations',
                    'varModuleClass' => 'OrganizationsController',
                    'varModuleNameSpace' => 'Powerpanel\Organizations\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Organizations')->first();
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


        $pageModuleCode = DB::table('module')->where('varTitle', 'Organizations')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Organizations'))->first();
        
         DB::table('organizations')->insert([
            'varTitle' => 'Organization 1',
            'txtShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varDesignation' => 'Designation 1',
            'varMetaTitle' => 'Organization 1',
            'varMetaKeyword' => 'Organization 1',
            'varMetaDescription' => 'Organization 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
         DB::table('organizations')->insert([
            'varTitle' => 'Organization 2',
            'txtShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varDesignation' => 'Designation 2',
            'varMetaTitle' => 'Organization 2',
            'varMetaKeyword' => 'Organization 2',
            'varMetaDescription' => 'Organization 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
         DB::table('organizations')->insert([
            'varTitle' => 'Organization 3',
            'txtShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varDesignation' => 'Designation 3',
            'varMetaTitle' => 'Organization 3',
            'varMetaKeyword' => 'Organization 3',
            'varMetaDescription' => 'Organization 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Organizations'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Organizations')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Organizations',
                    'varMetaKeyword' => 'Organizations',
                    'varMetaDescription' => 'Organizations',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Organizations'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Organizations')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Organizations',
                    'varMetaKeyword' => 'Organizations',
                    'varMetaDescription' => 'Organizations',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Organizations')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'organizations')->first();
        
        if (Schema::hasTable('visualcomposer')) {
            //Adding Organizations Module In visual composer
            $organizationsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Organizations')->where('fkParentID', '0')->first();

            if (!isset($organizationsModule->id) || empty($organizationsModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Organizations',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $organizationsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Organizations')->where('fkParentID', '0')->first();

            $organizationsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Organizations')->where('fkParentID', '<>', '0')->first();

            if (!isset($organizationsChild->id) || empty($organizationsChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $organizationsModule->id,
                    'varTitle' => 'All Organizations',
                    'varIcon' => 'fa fa-university',
                    'varClass' => 'organizations',
                    'varTemplateName' => 'organizations::partial.organizations',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }

}
