<?php
namespace Database\Seeders;

use Carbon\Carbon;
use DB;
use Illuminate\Database\Seeder;

class PollTableSeeder extends Seeder
{
    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Online Polling')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Online Polling',
                    'varModuleName' => 'online-polling',
                    'varTableName' => 'polls',
                    'varModelName' => 'Poll',
                    'varModuleClass' => 'PollsController',
                    'varModuleNameSpace' => 'Powerpanel\OnlinePolling\\',
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
                    'varTitle' => 'Online Polling',
                    'varModuleName' => 'online-polling',
                    'varTableName' => 'polls',
                    'varModelName' => 'Poll',
                    'varModuleClass' => 'PollsController',
                    'varModuleNameSpace' => 'Powerpanel\OnlinePolling\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Online Polling')->first();
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
                $roleObj = DB::table('roles')->select('id')->get();
                if ($roleObj->count() > 0) {
                    foreach ($roleObj as $rkey => $rvalue) {
                        $value = [
                            'permission_id' => $id,
                            'role_id' => $rvalue->id,
                        ];
                        DB::table('role_has_permissions')->insert($value);
                    }
                }
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'Online Polling')->first();
            $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
            $intFKModuleCode = $pageModuleCode->id;

            $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Online Polling'))->first();

            if (!isset($exists->id)) {
                if (\Schema::hasColumn('cms_page', 'chrMain')) {
                    DB::table('cms_page')->insert([
                        'varTitle' => htmlspecialchars_decode('Online Polling'),
                        'intAliasId' => \App\Helpers\MyLibrary::insertAlias(\App\Http\Traits\slug::create_slug(htmlspecialchars_decode('Online Polling')), $cmsModuleCode->id),
                        'intFKModuleCode' => $intFKModuleCode,
                        'txtDescription' => '',
                        'chrPublish' => 'Y',
                        'chrMain' => 'Y',
                        'chrDelete' => 'N',
                        'varMetaTitle' => 'Online Polling',
                        'varMetaKeyword' => 'Online Polling',
                        'varMetaDescription' => 'Online Polling',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                } else {
                    DB::table('cms_page')->insert([
                        'varTitle' => htmlspecialchars_decode('Online Polling'),
                        'intAliasId' => \App\Helpers\MyLibrary::insertAlias(\App\Http\Traits\slug::create_slug(htmlspecialchars_decode('Online Polling')), $cmsModuleCode->id),
                        'intFKModuleCode' => $intFKModuleCode,
                        'txtDescription' => '',
                        'chrPublish' => 'Y',
                        'chrDelete' => 'N',
                        'varMetaTitle' => 'Online Polling',
                        'varMetaKeyword' => 'Online Polling',
                        'varMetaDescription' => 'Online Polling',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ]);
                }
            }
        }
    }
}
