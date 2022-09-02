<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use DB;

class FormsAndFeesTableSeeder extends Seeder
{
  public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Forms and Fees')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Forms and Fees',
                    'varModuleName' => 'forms-and-fees',
                    'varTableName' => 'forms_and_fees',
                    'varModelName' => 'FormsAndFees',
                    'varModuleClass' => 'FormsAndFeesController',
                    'varModuleNameSpace' => 'Powerpanel\FormsAndFees\\',
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
                    '`varTitle`' => 'Forms and Fees',
                    'varModuleName' => 'forms-and-fees',
                    'varTableName' => 'forms_and_fees',
                    'varModelName' => 'FormsAndFees',
                    'varModuleClass' => 'FormsAndFeesController',
                    'varModuleNameSpace' => 'Powerpanel\FormsAndFees\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Forms and Fees')->first();
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

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Forms and Fees'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Forms and Fees'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Forms and Fees')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'FormsAndFees',
                    'varMetaKeyword' => 'FormsAndFees',
                    'varMetaDescription' => 'FormsAndFees',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Forms and Fees'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Forms and Fees')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'FormsAndFees',
                    'varMetaKeyword' => 'FormsAndFees',
                    'varMetaDescription' => 'FormsAndFees',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
        //Adding Forms and Fees Module In visual composer
        if (Schema::hasTable('visualcomposer')) {
            $netFormsandFeesModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Forms and Fees')->where('fkParentID', '0')->first();

            if (!isset($netFormsandFeesModule->id) || empty($netFormsandFeesModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Forms and Fees',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $netFormsandFeesModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Forms and Fees')->where('fkParentID', '0')->first();

            $netFormsandFeesChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Forms and Fees')->where('fkParentID', '<>', '0')->first();

            if (!isset($netFormsandFeesChild->id) || empty($netFormsandFeesChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netFormsandFeesModule->id,
                    'varTitle' => 'Forms and Fees',
                    'varIcon' => 'fa fa-file-text-o',
                    'varClass' => 'forms-and-fees',
                    'varTemplateName' => 'forms-and-fees::partial.formsandfees',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestformsandfees = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Forms and Fees')->where('fkParentID', '0')->first();

            if (!isset($latestformsandfees->id) || empty($latestformsandfees->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netFormsandFeesModule->id,
                    'varTitle' => 'All Forms and Fees',
                    'varIcon' => 'fa fa-file-text-o',
                    'varClass' => 'forms-and-fees-template',
                    'varTemplateName' => 'forms-and-fees::partial.all-formsandfees',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
    }
}
