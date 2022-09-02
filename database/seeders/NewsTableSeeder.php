<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class NewsTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'News')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'News',
                    'varModuleName' => 'news',
                    'varTableName' => 'news',
                    'varModelName' => 'News',
                    'varModuleClass' => 'NewsController',
                    'varModuleNameSpace' => 'Powerpanel\News\\',
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
                    'varTitle' => 'News',
                    'varModuleName' => 'news',
                    'varTableName' => 'news',
                    'varModelName' => 'News',
                    'varModuleClass' => 'NewsController',
                    'varModuleNameSpace' => 'Powerpanel\News\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'News')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'News Category')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('News'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('News'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('News')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'News',
                    'varMetaKeyword' => 'News',
                    'varMetaDescription' => 'News',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('News'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('News')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'News',
                    'varMetaKeyword' => 'News',
                    'varMetaDescription' => 'News',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'News')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'news')->first();

        $pageModuleCodealias = DB::table('module')->select('id')->where('varTitle', 'News')->first();
        $intFKModuleCodealias = $pageModuleCodealias->id;

        DB::table('news')->insert([
            'varTitle' => 'News 1',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('News 1')), $intFKModuleCodealias),
            'txtCategories' => 1,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'News 1',
            'varMetaKeyword' => 'News 1',
            'varMetaDescription' => 'News 1',
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('news')->insert([
            'varTitle' => 'News 2',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('News 2')), $intFKModuleCodealias),
            'txtCategories' => 2,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'News 2',
            'varMetaKeyword' => 'News 2',
            'varMetaDescription' => 'News 2',
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('news')->insert([
            'varTitle' => 'News 3',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('News 3')), $intFKModuleCodealias),
            'txtCategories' => 3,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'News 3',
            'varMetaKeyword' => 'News 3',
            'varMetaDescription' => 'News 3',
            'chrMain' => 'Y',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if (Schema::hasTable('visualcomposer')) {
            //Adding News Module In visual composer
            $newsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'News')->where('fkParentID', '0')->first();

            if (!isset($newsModule->id) || empty($newsModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'News',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $newsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'News')->where('fkParentID', '0')->first();

            $newsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'News')->where('fkParentID', '<>', '0')->first();

            if (!isset($newsChild->id) || empty($newsChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $newsModule->id,
                    'varTitle' => 'News',
                    'varIcon' => 'fa fa-newspaper-o',
                    'varClass' => 'news',
                    'varTemplateName' => 'news::partial.news',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestNews = DB::table('visualcomposer')->select('id')->where('varTitle', 'All News')->where('fkParentID', '0')->first();

            if (!isset($latestNews->id) || empty($latestNews->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $newsModule->id,
                    'varTitle' => 'All News',
                    'varIcon' => 'fa fa-newspaper-o',
                    'varClass' => 'news-template',
                    'varTemplateName' => 'news::partial.all-news',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

}
