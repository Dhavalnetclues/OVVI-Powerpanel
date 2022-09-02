<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AlertsTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Alerts')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Alerts',
                    'varModuleName' => 'alerts',
                    'varTableName' => 'alerts',
                    'varModelName' => 'Alerts',
                    'varModuleClass' => 'AlertsController',
                    'varModuleNameSpace' => 'Powerpanel\Alerts\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'N',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('module')->insert([
                    'varTitle' => 'Alerts',
                    'varModuleName' => 'alerts',
                    'varTableName' => 'alerts',
                    'varModelName' => 'Alerts',
                    'varModuleClass' => 'AlertsController',
                    'varModuleNameSpace' => 'Powerpanel\Alerts\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'N',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'Alerts')->first();
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
                    DB::table('permission_role')->insert($value);
                }
            }
        }

        $pageModuleCode = DB::table('module')->where('varTitle', 'Alerts')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Alerts'))->first();
        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Alerts'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Alerts')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Alerts',
                    'varMetaKeyword' => 'Alerts',
                    'varMetaDescription' => 'Alerts',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Alerts'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Alerts')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Alerts',
                    'varMetaKeyword' => 'Alerts',
                    'varMetaDescription' => 'Alerts',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Alerts')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'alerts')->first();

        if (Schema::hasTable('visualcomposer')) {
            //Adding Alerts Module In visual composer
            $alertsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Alerts')->where('fkParentID', '0')->first();

            if (!isset($alertsModule->id) || empty($alertsModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Alerts',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $alertsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Alerts')->where('fkParentID', '0')->first();

            $alertsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Alerts')->where('fkParentID', '<>', '0')->first();

            if (!isset($alertsChild->id) || empty($alertsChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $alertsModule->id,
                    'varTitle' => 'Alerts',
                    'varIcon' => 'ri-alert-fill',
                    'varClass' => 'alerts',
                    'varTemplateName' => 'alerts::partial.alerts',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $AllAlerts = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Alerts')->where('fkParentID', '0')->first();

            if (!isset($AllAlerts->id) || empty($AllAlerts->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $alertsModule->id,
                    'varTitle' => 'All Alerts',
                    'varIcon' => 'ri-alert-fill',
                    'varClass' => 'alerts-template',
                    'varTemplateName' => 'alerts::partial.all-alerts',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

}
