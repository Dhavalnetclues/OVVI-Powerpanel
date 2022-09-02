<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class TeamTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Team')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Team',
                    'varModuleName' => 'team',
                    'varTableName' => 'team',
                    'varModelName' => 'Team',
                    'varModuleClass' => 'TeamController',
                    'varModuleNameSpace' => 'Powerpanel\Team\\',
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
                    'varTitle' => 'Team',
                    'varModuleName' => 'team',
                    'varTableName' => 'team',
                    'varModelName' => 'Team',
                    'varModuleClass' => 'TeamController',
                    'varModuleNameSpace' => 'Powerpanel\Team\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Team')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Team')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Team'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Team'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Team')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Team',
                    'varMetaKeyword' => 'Team',
                    'varMetaDescription' => 'Team',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Team'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Team')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Team',
                    'varMetaKeyword' => 'Team',
                    'varMetaDescription' => 'Team',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Team')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'team')->first();
        $pageModuleCodealias = DB::table('module')->select('id')->where('varTitle', 'Team')->first();
        $intFKModuleCodealias = $pageModuleCodealias->id;

        DB::table('team')->insert([
            'varTitle' => 'Team 1',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Team 1')), $intFKModuleCodealias),
            'txtDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'intDisplayOrder' => '1',
            'varMetaTitle' => 'Team 1',
            'varMetaKeyword' => 'Team 1',
            'varMetaDescription' => 'Team 1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('team')->insert([
            'varTitle' => 'Team 2',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Team 2')), $intFKModuleCodealias),
            'txtDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'intDisplayOrder' => '2',
            'varMetaTitle' => 'Team 2',
            'varMetaKeyword' => 'Team 2',
            'varMetaDescription' => 'Team 2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('team')->insert([
            'varTitle' => 'Team 3',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Team 3')), $intFKModuleCodealias),
            'txtDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'intDisplayOrder' => '3',
            'varMetaTitle' => 'Team 3',
            'varMetaKeyword' => 'Team 3',
            'varMetaDescription' => 'Team 3',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //Adding Team Module In visual composer
        if (Schema::hasTable('visualcomposer')) {
            $TeamModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Team')->where('fkParentID', '0')->first();

            if (!isset($TeamModule->id) || empty($TeamModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Team',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $TeamModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Team')->where('fkParentID', '0')->first();

            $TeamChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Team')->where('fkParentID', '<>', '0')->first();

            if (!isset($TeamChild->id) || empty($TeamChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $TeamModule->id,
                    'varTitle' => 'Team',
                    'varIcon' => 'fa fa-user-o',
                    'varClass' => 'team',
                    'varTemplateName' => 'team::partial.team',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestTeam = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Team')->where('fkParentID', '0')->first();

            if (!isset($latestTeam->id) || empty($latestTeam->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $TeamModule->id,
                    'varTitle' => 'All Team',
                    'varIcon' => 'fa fa-user-o',
                    'varClass' => 'team-template',
                    'varTemplateName' => 'team::partial.all-team',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

}
