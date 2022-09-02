<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventCategoryTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Event Category')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Event Category',
                    'varModuleName' => 'event-category',
                    'varTableName' => 'event_category',
                    'varModelName' => 'EventCategory',
                    'varModuleClass' => 'EventCategoryController',
                    'varModuleNameSpace' => 'Powerpanel\EventCategory\\',
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
                    'varTitle' => 'Event Category',
                    'varModuleName' => 'event-category',
                    'varTableName' => 'event_category',
                    'varModelName' => 'EventCategory',
                    'varModuleClass' => 'EventCategoryController',
                    'varModuleNameSpace' => 'Powerpanel\EventCategory\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Event Category')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Event Category')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Event Category'))->first();

        DB::table('event_category')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Event Category 1')), $intFKModuleCode),
            'varTitle' => 'Event Category 1',
            'txtDescription' => '',
            'varMetaTitle' => 'Event Category 1',
            'varMetaKeyword' => 'Event Category 1',
            'varMetaDescription' => 'Event Category 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('event_category')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Event Category 2')), $intFKModuleCode),
            'varTitle' => 'Event Category 2',
            'txtDescription' => '',
            'varMetaTitle' => 'Event Category 2',
            'varMetaKeyword' => 'Event Category 2',
            'varMetaDescription' => 'Event Category 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('event_category')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Event Category 3')), $intFKModuleCode),
            'varTitle' => 'Event Category 3',
            'txtDescription' => '',
            'varMetaTitle' => 'Event Category 3',
            'varMetaKeyword' => 'Event Category 3',
            'varMetaDescription' => 'Event Category 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Event Category'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Event Category')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Event Category',
                    'varMetaKeyword' => 'Event Category',
                    'varMetaDescription' => 'Event Category',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Event Category'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Event Category')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Event Category',
                    'varMetaKeyword' => 'Event Category',
                    'varMetaDescription' => 'Event Category',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Event Category')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'event_category')->first();
    }

}
