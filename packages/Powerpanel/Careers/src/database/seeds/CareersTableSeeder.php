<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class CareersTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Careers')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Careers',
                    'varModuleName' => 'careers',
                    'varTableName' => 'careers',
                    'varModelName' => 'Careers',
                    'varModuleClass' => 'CareersController',
                    'varModuleNameSpace' => 'Powerpanel\Careers\\',
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
                    'varTitle' => 'Careers',
                    'varModuleName' => 'careers',
                    'varTableName' => 'careers',
                    'varModelName' => 'Careers',
                    'varModuleClass' => 'CareersController',
                    'varModuleNameSpace' => 'Powerpanel\Careers\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Careers')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Careers')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Careers'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Careers'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Careers')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Careers',
                    'varMetaKeyword' => 'Careers',
                    'varMetaDescription' => 'Careers',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Careers'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Careers')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Careers',
                    'varMetaKeyword' => 'Careers',
                    'varMetaDescription' => 'Careers',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Careers')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'careers')->first();

        DB::table('careers')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Careers 1')), $intFKModuleCode),
            'varTitle' => 'Careers 1',
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'intDisplayOrder' => '1',
            'varMetaTitle' => 'Careers 1',
            'varMetaKeyword' => 'Careers 1',
            'varMetaDescription' => 'Careers 1',
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('careers')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Careers 2')), $intFKModuleCode),
            'varTitle' => 'Careers 2',
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'intDisplayOrder' => '2',
            'varMetaTitle' => 'Careers 2',
            'varMetaKeyword' => 'Careers 2',
            'varMetaDescription' => 'Careers 2',
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('careers')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Careers 3')), $intFKModuleCode),
            'varTitle' => 'Careers 3',
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'intDisplayOrder' => '3',
            'varMetaTitle' => 'Careers 3',
            'varMetaKeyword' => 'Careers 3',
            'varMetaDescription' => 'Careers 3',
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }

}
