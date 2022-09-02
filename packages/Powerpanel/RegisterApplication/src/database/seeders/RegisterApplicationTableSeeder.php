<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use DB;

class RegisterApplicationTableSeeder extends Seeder
{

     public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Register of Applications')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Register of Applications',
                    'varModuleName' => 'register-application',
                    'varTableName' => 'register_application',
                    'varModelName' => 'RegisterApplication',
                    'varModuleClass' => 'RegisterApplicationController',
                    'varModuleNameSpace' => 'Powerpanel\RegisterApplication\\',
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
                    '`varTitle`' => 'Register of Applications',
                    'varModuleName' => 'register-application',
                    'varTableName' => 'register_application',
                    'varModelName' => 'RegisterApplication',
                    'varModuleClass' => 'RegisterApplicationController',
                    'varModuleNameSpace' => 'Powerpanel\RegisterApplication\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Register of Applications')->first();
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

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Register of Applications'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Register of Applications'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Register of Applications')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'RegisterApplication',
                    'varMetaKeyword' => 'RegisterApplication',
                    'varMetaDescription' => 'RegisterApplication',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Register of Applications'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Register of Applications')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'RegisterApplication',
                    'varMetaKeyword' => 'RegisterApplication',
                    'varMetaDescription' => 'RegisterApplication',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
        //Adding Register of Applications Module In visual composer
        if (Schema::hasTable('visualcomposer')) {
            $netRegisterofApplicationsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Register of Applications')->where('fkParentID', '0')->first();

            if (!isset($netRegisterofApplicationsModule->id) || empty($netRegisterofApplicationsModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Register of Applications',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $netRegisterofApplicationsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Register of Applications')->where('fkParentID', '0')->first();

            $netRegisterofApplicationsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Register of Applications')->where('fkParentID', '<>', '0')->first();

            if (!isset($netRegisterofApplicationsChild->id) || empty($netRegisterofApplicationsChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netRegisterofApplicationsModule->id,
                    'varTitle' => 'Register of Applications',
                    'varIcon' => 'fa fa-registered',
                    'varClass' => 'register-application',
                    'varTemplateName' => 'register-application::partial.registerapplication',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestregisterapplication = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Register of Applications')->where('fkParentID', '0')->first();

            if (!isset($latestregisterapplication->id) || empty($latestregisterapplication->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netRegisterofApplicationsModule->id,
                    'varTitle' => 'All Register of Applications',
                    'varIcon' => 'fa fa-registered',
                    'varClass' => 'register-application-template',
                    'varTemplateName' => 'register-application::partial.all-registerapplication',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
    }
}
