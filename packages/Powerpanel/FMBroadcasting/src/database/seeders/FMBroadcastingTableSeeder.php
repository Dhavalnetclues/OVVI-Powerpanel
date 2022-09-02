<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use DB;

class FMBroadcastingTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'FM Broadcasting')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'FM Broadcasting',
                    'varModuleName' => 'fmbroadcasting',
                    'varTableName' => 'fmbroadcasting',
                    'varModelName' => 'FMBroadcasting',
                    'varModuleClass' => 'FMBroadcastingController',
                    'varModuleNameSpace' => 'Powerpanel\FMBroadcasting\\',
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
                    '`varTitle`' => 'FM Broadcasting',
                    'varModuleName' => 'fmbroadcasting',
                    'varTableName' => 'fmbroadcasting',
                    'varModelName' => 'FMBroadcasting',
                    'varModuleClass' => 'FMBroadcastingController',
                    'varModuleNameSpace' => 'Powerpanel\FMBroadcasting\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'FM Broadcasting')->first();
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

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('FM Broadcasting'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('FM Broadcasting'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('FM Broadcasting')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'FM Broadcasting',
                    'varMetaKeyword' => 'FM Broadcasting',
                    'varMetaDescription' => 'FM Broadcasting',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('FM Broadcasting'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('FM Broadcasting')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'FM Broadcasting',
                    'varMetaKeyword' => 'FM Broadcasting',
                    'varMetaDescription' => 'FM Broadcasting',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
           //Adding FM Broadcasting Module In visual composer
        if (Schema::hasTable('visualcomposer')) {
            $netFMBroadcastingModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'FM Broadcasting')->where('fkParentID', '0')->first();

            if (!isset($netFMBroadcastingModule->id) || empty($netFMBroadcastingModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'FM Broadcasting',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $netFMBroadcastingModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'FM Broadcasting')->where('fkParentID', '0')->first();

            $netFMBroadcastingChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'FM Broadcasting')->where('fkParentID', '<>', '0')->first();

            if (!isset($netFMBroadcastingChild->id) || empty($netFMBroadcastingChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netFMBroadcastingModule->id,
                    'varTitle' => 'FM Broadcasting',
                    'varIcon' => 'fa fa-bullhorn',
                    'varClass' => 'fmbroadcasting',
                    'varTemplateName' => 'fmbroadcasting::partial.fmbroadcasting',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestFMBroadcasting = DB::table('visualcomposer')->select('id')->where('varTitle', 'All FM Broadcasting')->where('fkParentID', '0')->first();

            if (!isset($latestFMBroadcasting->id) || empty($latestFMBroadcasting->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netFMBroadcastingModule->id,
                    'varTitle' => 'All FM Broadcasting',
                    'varIcon' => 'fa fa-bullhorn',
                    'varClass' => 'fmbroadcasting-template',
                    'varTemplateName' => 'fmbroadcasting::partial.all-fmbroadcasting',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
        
    }

}
