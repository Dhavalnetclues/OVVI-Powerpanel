<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use DB;

class ServicesTableSeeder extends Seeder
{
    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Services')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Services',
                    'varModuleName' => 'services',
                    'varTableName' => 'services',
                    'varModelName' => 'Services',
                    'varModuleClass' => 'ServicesController',
                    'varModuleNameSpace' => 'Powerpanel\Services\\',
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
                    'varTitle' => 'Services',
                    'varModuleName' => 'services',
                    'varTableName' => 'services',
                    'varModelName' => 'Services',
                    'varModuleClass' => 'ServicesController',
                    'varModuleNameSpace' => 'Powerpanel\Services\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Services')->first();
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

        }

        $pageModuleCode = DB::table('module')->where('varTitle', 'Services')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Services'))->first();

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Services'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Services')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrMain' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Services',
                    'varMetaKeyword' => 'Services',
                    'varMetaDescription' => 'Services',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Services'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Services')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Services',
                    'varMetaKeyword' => 'Services',
                    'varMetaDescription' => 'Services',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Services')->first();

        $moduleCode = DB::table('module')->select('id')->where('varModuleName', 'services')->first();

        DB::table('services')->insert([
            'varTitle' => 'Service 1',
            'serviceCode' => 'abc',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Service 1')), $moduleCode->id),
            'intFkCategory' => 1,
            'txtDescription' => '',
            'varMetaTitle' => 'Service 1',
            'varMetaKeyword' => 'Service 1',
            'varMetaDescription' => 'Service 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('services')->insert([
            'varTitle' => 'Service 2',
            'serviceCode' => '123',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Service 2')), $moduleCode->id),
            'intFkCategory' => 2,
            'txtDescription' => '',
            'varMetaTitle' => 'Service 2',
            'varMetaKeyword' => 'Service 2',
            'varMetaDescription' => 'Service 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        DB::table('services')->insert([
            'varTitle' => 'Service 3',
            'serviceCode' => 'xyz',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Service 3')), $moduleCode->id),
            'intFkCategory' => 3,
            'txtDescription' => '',
            'varMetaTitle' => 'Service 3',
            'varMetaKeyword' => 'Service 3',
            'varMetaDescription' => 'Service 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        //Adding Blogs Module In visual composer
        // if (Schema::hasTable('visualcomposer')) {
        //     $netBlogsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Blogs')->where('fkParentID', '0')->first();

        //     if (!isset($netBlogsModule->id) || empty($netBlogsModule->id)) {
        //         DB::table('visualcomposer')->insert([
        //             'fkParentID' => '0',
        //             'varTitle' => 'Blogs',
        //             'varIcon' => '',
        //             'varClass' => '',
        //             'varTemplateName' => '',
        //             'varModuleID' => $pageModuleCode->id,
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]);
        //     }

        //     $netBlogsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Blogs')->where('fkParentID', '0')->first();

        //     $netBlogsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Blogs')->where('fkParentID', '<>', '0')->first();

        //     if (!isset($netBlogsChild->id) || empty($netBlogsChild->id)) {
        //         DB::table('visualcomposer')->insert([
        //             'fkParentID' => $netBlogsModule->id,
        //             'varTitle' => 'Blogs',
        //             'varIcon' => 'fa fa-briefcase',
        //             'varClass' => 'blogs',
        //             'varTemplateName' => 'blogs::partial.blogs',
        //             'varModuleID' => $pageModuleCode->id,
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]);
        //     }

        //     $latestBlog = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Blogs')->where('fkParentID', '0')->first();

        //     if (!isset($latestBlog->id) || empty($latestBlog->id)) {
        //         DB::table('visualcomposer')->insert([
        //             'fkParentID' => $netBlogsModule->id,
        //             'varTitle' => 'All Blogs',
        //             'varIcon' => 'fa fa-briefcase',
        //             'varClass' => 'blogs-template',
        //             'varTemplateName' => 'blogs::partial.all-blogs',
        //             'varModuleID' => $pageModuleCode->id,
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]);
        //     }
        // }
    }
}
