<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DepartmentTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Department')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Department',
                    'varModuleName' => 'department',
                    'varTableName' => 'department',
                    'varModelName' => 'Department',
                    'varModuleClass' => 'DepartmentController',
                    'varModuleNameSpace' => 'Powerpanel\Department\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('module')->insert([
                    'varTitle' => 'Department',
                    'varModuleName' => 'department',
                    'varTableName' => 'department',
                    'varModelName' => 'Department',
                    'varModuleClass' => 'DepartmentController',
                    'varModuleNameSpace' => 'Powerpanel\Department\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'Department')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Department')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        DB::table('department')->insert([
            'varTitle' => 'Department 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('department')->insert([
            'varTitle' => 'Department 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('department')->insert([
            'varTitle' => 'Department 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Department'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Department'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Department')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Department',
                    'varMetaKeyword' => 'Department',
                    'varMetaDescription' => 'Department',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Department'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Department')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Department',
                    'varMetaKeyword' => 'Department',
                    'varMetaDescription' => 'Department',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Department')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'department')->first();

        if (Schema::hasTable('visualcomposer')) {
            //Adding Department Module In visual composer
            $departmentModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Department')->where('fkParentID', '0')->first();

            if (!isset($departmentModule->id) || empty($departmentModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Department',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $departmentModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Department')->where('fkParentID', '0')->first();

            $departmentChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Department')->where('fkParentID', '<>', '0')->first();

            if (!isset($departmentChild->id) || empty($departmentChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $departmentModule->id,
                    'varTitle' => 'Department',
                    'varIcon' => 'fa fa-building-o',
                    'varClass' => 'department',
                    'varTemplateName' => 'department::partial.department',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
            $AllDepartment = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Department')->where('fkParentID', '0')->first();

            if (!isset($AllDepartment->id) || empty($AllDepartment->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $departmentModule->id,
                    'varTitle' => 'All Department',
                    'varIcon' => 'fa fa-building-o',
                    'varClass' => 'department-template',
                    'varTemplateName' => 'department::partial.all-department',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

}
