<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use DB;

class LicenceRegisterTableSeeder extends Seeder
{

       public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Licence Register')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Licence Register',
                    'varModuleName' => 'licence-register',
                    'varTableName' => 'licence_register',
                    'varModelName' => 'LicenceRegister',
                    'varModuleClass' => 'LicenceRegisterController',
                    'varModuleNameSpace' => 'Powerpanel\LicenceRegister\\',
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
                    '`varTitle`' => 'Licence Register',
                    'varModuleName' => 'licence-register',
                    'varTableName' => 'licence_register',
                    'varModelName' => 'LicenceRegister',
                    'varModuleClass' => 'LicenceRegisterController',
                    'varModuleNameSpace' => 'Powerpanel\LicenceRegister\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Licence Register')->first();
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

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Licence Register'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Licence Register'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Licence Register')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'LicenceRegister',
                    'varMetaKeyword' => 'LicenceRegister',
                    'varMetaDescription' => 'LicenceRegister',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Licence Register'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Licence Register')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'LicenceRegister',
                    'varMetaKeyword' => 'LicenceRegister',
                    'varMetaDescription' => 'LicenceRegister',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
        //Adding Licence Register Module In visual composer
        if (Schema::hasTable('visualcomposer')) {
            $netLicenceRegisterModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Licence Register')->where('fkParentID', '0')->first();

            if (!isset($netLicenceRegisterModule->id) || empty($netLicenceRegisterModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Licence Register',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $netLicenceRegisterModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Licence Register')->where('fkParentID', '0')->first();

            $netLicenceRegisterChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Licence Register')->where('fkParentID', '<>', '0')->first();

            if (!isset($netLicenceRegisterChild->id) || empty($netLicenceRegisterChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netLicenceRegisterModule->id,
                    'varTitle' => 'Licence Register',
                    'varIcon' => 'fa fa-id-badge',
                    'varClass' => 'licence-register',
                    'varTemplateName' => 'licence-register::partial.licenceregister',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestlicenceregisterapplication = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Licence Register')->where('fkParentID', '0')->first();

            if (!isset($latestlicenceregisterapplication->id) || empty($latestlicenceregisterapplication->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netLicenceRegisterModule->id,
                    'varTitle' => 'All Licence Register',
                    'varIcon' => 'fa fa-id-badge',
                    'varClass' => 'licence-register-template',
                    'varTemplateName' => 'licence-register::partial.all-licenceregister',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
    }

}
