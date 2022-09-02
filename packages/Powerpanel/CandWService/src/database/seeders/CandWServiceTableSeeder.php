<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use DB;

class CandWServiceTableSeeder extends Seeder
{

     public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'CandWService')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'CandWService',
                    'varModuleName' => 'candwservice',
                    'varTableName' => 'candwservice',
                    'varModelName' => 'CandWService',
                    'varModuleClass' => 'CandWServiceController',
                    'varModuleNameSpace' => 'Powerpanel\CandWService\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish, reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('module')->insert([
                    '`varTitle`' => 'CandWService',
                    'varModuleName' => 'candwservice',
                    'varTableName' => 'candwservice',
                    'varModelName' => 'CandWService',
                    'varModuleClass' => 'CandWServiceController',
                    'varModuleNameSpace' => 'Powerpanel\CandWService\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish, reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'CandWService')->first();
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
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('CandWService'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('CandWService'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('CandWService')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'CandWService',
                    'varMetaKeyword' => 'CandWService',
                    'varMetaDescription' => 'CandWService',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('CandWService'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('CandWService')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'CandWService',
                    'varMetaKeyword' => 'CandWService',
                    'varMetaDescription' => 'CandWService',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
            //Adding CandWService Module In visual composer
        if (Schema::hasTable('visualcomposer')) {
            $netCandWServiceModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'CandWService')->where('fkParentID', '0')->first();

            if (!isset($netCandWServiceModule->id) || empty($netCandWServiceModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'CandWService',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $netCandWServiceModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'CandWService')->where('fkParentID', '0')->first();

            $netCandWServiceChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'CandWService')->where('fkParentID', '<>', '0')->first();

            if (!isset($netCandWServiceChild->id) || empty($netCandWServiceChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netCandWServiceModule->id,
                    'varTitle' => 'CandWService',
                    'varIcon' => 'fa fa-cogs',
                    'varClass' => 'candwservice',
                    'varTemplateName' => 'candwservice::partial.candwservice',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestBlog = DB::table('visualcomposer')->select('id')->where('varTitle', 'All CandWService')->where('fkParentID', '0')->first();

            if (!isset($latestBlog->id) || empty($latestBlog->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netCandWServiceModule->id,
                    'varTitle' => 'All CandWService',
                    'varIcon' => 'fa fa-cogs',
                    'varClass' => 'candwservice-template',
                    'varTemplateName' => 'candwservice::partial.all-candwservice',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

}
