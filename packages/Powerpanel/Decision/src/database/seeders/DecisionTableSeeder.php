<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DecisionTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Decision')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Decision',
                    'varModuleName' => 'decision',
                    'varTableName' => 'decision',
                    'varModelName' => 'Decision',
                    'varModuleClass' => 'DecisionController',
                    'varModuleNameSpace' => 'Powerpanel\Decision\\',
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
                    'varTitle' => 'Decision',
                    'varModuleName' => 'decision',
                    'varTableName' => 'decision',
                    'varModelName' => 'Decision',
                    'varModuleClass' => 'DecisionController',
                    'varModuleNameSpace' => 'Powerpanel\Decision\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Decision')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Decision Category')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Decision'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Decision'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Decision')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Decision',
                    'varMetaKeyword' => 'Decision',
                    'varMetaDescription' => 'Decision',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Decision'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Decision')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Decision',
                    'varMetaKeyword' => 'Decision',
                    'varMetaDescription' => 'Decision',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Decision')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'decision')->first();

        $pageModuleCodealias = DB::table('module')->select('id')->where('varTitle', 'Decision')->first();
        $intFKModuleCodealias = $pageModuleCodealias->id;

        DB::table('decision')->insert([
            'varTitle' => 'Decision 1',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Decision 1')), $intFKModuleCodealias),
            'txtCategories' => 1,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Decision 1',
            'varMetaKeyword' => 'Decision 1',
            'varMetaDescription' => 'Decision 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('decision')->insert([
            'varTitle' => 'Decision 2',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Decision 2')), $intFKModuleCodealias),
            'txtCategories' => 2,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Decision 2',
            'varMetaKeyword' => 'Decision 2',
            'varMetaDescription' => 'Decision 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('decision')->insert([
            'varTitle' => 'Decision 3',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Decision 3')), $intFKModuleCodealias),
            'txtCategories' => 3,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Decision 3',
            'varMetaKeyword' => 'Decision 3',
            'varMetaDescription' => 'Decision 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if (Schema::hasTable('visualcomposer')) {
            //Adding Decision Module In visual composer
            $decisionModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Decision')->where('fkParentID', '0')->first();

            if (!isset($decisionModule->id) || empty($decisionModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Decision',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $decisionModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Decision')->where('fkParentID', '0')->first();

            $decisionChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Decision')->where('fkParentID', '<>', '0')->first();

            if (!isset($decisionChild->id) || empty($decisionChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $decisionModule->id,
                    'varTitle' => 'Decision',
                    'varIcon' => 'fa fa-book',
                    'varClass' => 'publication',
                    'varTemplateName' => 'decision::partial.decision',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestPublication = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Decision')->where('fkParentID', '0')->first();

            if (!isset($latestPublication->id) || empty($latestPublication->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $decisionModule->id,
                    'varTitle' => 'All Decision',
                    'varIcon' => 'fa fa-book',
                    'varClass' => 'publication-template',
                    'varTemplateName' => 'decision::partial.all-decision',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

}
