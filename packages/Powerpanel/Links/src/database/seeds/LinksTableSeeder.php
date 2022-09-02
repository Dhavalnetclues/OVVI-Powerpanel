<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class LinksTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Links')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Links',
                    'varModuleName' => 'links',
                    'varTableName' => 'links',
                    'varModelName' => 'Links',
                    'varModuleClass' => 'LinksController',
                    'varModuleNameSpace' => 'Powerpanel\Links\\',
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
                    'varTitle' => 'Links',
                    'varModuleName' => 'links',
                    'varTableName' => 'links',
                    'varModelName' => 'Links',
                    'varModuleClass' => 'LinksController',
                    'varModuleNameSpace' => 'Powerpanel\Links\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Links')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Links')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Links'))->first();

        DB::table('links')->insert([
            'varTitle' => 'Link 1',
            'intFKCategory' => '1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('links')->insert([
            'varTitle' => 'Link 2',
            'intFKCategory' => '2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('links')->insert([
            'varTitle' => 'Link 3',
            'intFKCategory' => '3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Links'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Links')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Links',
                    'varMetaKeyword' => 'Links',
                    'varMetaDescription' => 'Links',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Links'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Links')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Links',
                    'varMetaKeyword' => 'Links',
                    'varMetaDescription' => 'Links',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Links')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'links')->first();

        if (Schema::hasTable('visualcomposer')) {
            //Adding Link Module In visual composer
            $linkModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Links')->where('fkParentID', '0')->first();

            if (!isset($linkModule->id) || empty($linkModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Links',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $linksModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Links')->where('fkParentID', '0')->first();

            $linksChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Links')->where('fkParentID', '<>', '0')->first();

            if (!isset($linksChild->id) || empty($linksChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $linksModule->id,
                    'varTitle' => 'Links',
                    'varIcon' => 'ri-external-link-line',
                    'varClass' => 'links',
                    'varTemplateName' => 'links::partial.links',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $AllLinks = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Links')->where('fkParentID', '0')->first();

            if (!isset($AllLinks->id) || empty($AllLinks->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $linksModule->id,
                    'varTitle' => 'All Links',
                    'varIcon' => 'ri-external-link-line',
                    'varClass' => 'links-template',
                    'varTemplateName' => 'links::partial.all-links',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }

}
