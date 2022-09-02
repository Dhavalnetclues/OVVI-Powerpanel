<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class BlogsTableSeeder extends Seeder
{
    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'blogs')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Blogs',
                    'varModuleName' => 'blogs',
                    'varTableName' => 'blogs',
                    'varModelName' => 'blogs',
                    'varModuleClass' => 'BlogsController',
                    'varModuleNameSpace' => 'Powerpanel\Blogs\\',
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
                    'varTitle' => 'Blogs',
                    'varModuleName' => 'blogs',
                    'varTableName' => 'blogs',
                    'varModelName' => 'blogs',
                    'varModuleClass' => 'BlogsController',
                    'varModuleNameSpace' => 'Powerpanel\Blogs\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Blogs')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Blog Category')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Blogs'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Blogs'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Blogs')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Blogs',
                    'varMetaKeyword' => 'Blogs',
                    'varMetaDescription' => 'Blogs',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Blogs'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Blogs')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Blogs',
                    'varMetaKeyword' => 'Blogs',
                    'varMetaDescription' => 'Blogs',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Blogs')->first();

        $moduleCode = DB::table('module')->select('id')->where('varModuleName', 'blogs')->first();

        DB::table('blogs')->insert([
            'varTitle' => 'Blogs 1',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Blogs 1')), $moduleCode->id),
            'intFkCategory' => 1,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Blogs 1',
            'varMetaKeyword' => 'Blogs 1',
            'varMetaDescription' => 'Blogs 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('blogs')->insert([
            'varTitle' => 'Blogs 2',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Blogs 2')), $moduleCode->id),
            'intFkCategory' => 2,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Blogs 2',
            'varMetaKeyword' => 'Blogs 2',
            'varMetaDescription' => 'Blogs 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('blogs')->insert([
            'varTitle' => 'Blogs 3',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Blogs 3')), $moduleCode->id),
            'intFkCategory' => 3,
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Blogs 3',
            'varMetaKeyword' => 'Blogs 3',
            'varMetaDescription' => 'Blogs 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //Adding Blogs Module In visual composer
        if (Schema::hasTable('visualcomposer')) {
            $netBlogsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Blogs')->where('fkParentID', '0')->first();

            if (!isset($netBlogsModule->id) || empty($netBlogsModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Blogs',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $netBlogsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Blogs')->where('fkParentID', '0')->first();

            $netBlogsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Blogs')->where('fkParentID', '<>', '0')->first();

            if (!isset($netBlogsChild->id) || empty($netBlogsChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netBlogsModule->id,
                    'varTitle' => 'Blogs',
                    'varIcon' => 'fa fa-briefcase',
                    'varClass' => 'blogs',
                    'varTemplateName' => 'blogs::partial.blogs',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestBlog = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Blogs')->where('fkParentID', '0')->first();

            if (!isset($latestBlog->id) || empty($latestBlog->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $netBlogsModule->id,
                    'varTitle' => 'All Blogs',
                    'varIcon' => 'fa fa-briefcase',
                    'varClass' => 'blogs-template',
                    'varTemplateName' => 'blogs::partial.all-blogs',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }
}
