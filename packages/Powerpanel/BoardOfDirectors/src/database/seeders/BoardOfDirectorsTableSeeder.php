<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use DB;

class BoardOfDirectorsTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Board of Directors')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Board of Directors',
                    'varModuleName' => 'boardofdirectors',
                    'varTableName' => 'board_of_directors',
                    'varModelName' => 'BoardOfDirectors',
                    'varModuleClass' => 'BoardOfDirectorsController',
                    'varModuleNameSpace' => 'Powerpanel\BoardOfDirectors\\',
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
                    'varTitle' => 'Board of Directors',
                    'varModuleName' => 'boardofdirectors',
                    'varTableName' => 'board_of_directors',
                    'varModelName' => 'BoardOfDirectors',
                    'varModuleClass' => 'BoardOfDirectorsController',
                    'varModuleNameSpace' => 'Powerpanel\BoardOfDirectors\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Board of Directors')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Board of Directors')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Board of Directors'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Board of Directors'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Board of Directors')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'BoardOfDirectors',
                    'varMetaKeyword' => 'BoardOfDirectors',
                    'varMetaDescription' => 'BoardOfDirectors',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Board of Directors'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Board of Directors')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'BoardOfDirectors',
                    'varMetaKeyword' => 'BoardOfDirectors',
                    'varMetaDescription' => 'BoardOfDirectors',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Board of Directors')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'boardofdirectors')->first();
        $pageModuleCodealias = DB::table('module')->select('id')->where('varTitle', 'Board of Directors')->first();
        $intFKModuleCodealias = $pageModuleCodealias->id;

        
           //Adding Board of Directors Module In visual composer
        if (Schema::hasTable('visualcomposer')) {
            $netBoardofDirectorsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Board of Directors')->where('fkParentID', '0')->first();

            if (!isset($netBoardofDirectorsModule->id) || empty($netBoardofDirectorsModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Board of Directors',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $netBoardofDirectorsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Board of Directors')->where('fkParentID', '0')->first();

            $netBoardofDirectorsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Board of Directors')->where('fkParentID', '<>', '0')->first();

            if (!isset($netBoardofDirectorsChild->id) || empty($netBoardofDirectorsChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netBoardofDirectorsModule->id,
                    'varTitle' => 'Board of Directors',
                    'varIcon' => 'fa fa-user',
                    'varClass' => 'boardofdirectors',
                    'varTemplateName' => 'boardofdirectors::partial.boardofdirectors',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestboardofdirectors = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Board of Directors')->where('fkParentID', '0')->first();

            if (!isset($latestboardofdirectors->id) || empty($latestboardofdirectors->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netBoardofDirectorsModule->id,
                    'varTitle' => 'All Board of Directors',
                    'varIcon' => 'fa fa-user',
                    'varClass' => 'boardofdirectors-template',
                    'varTemplateName' => 'boardofdirectors::partial.all-boardofdirectors',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
    }

}
