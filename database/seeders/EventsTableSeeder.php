<?php

namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class EventsTableSeeder extends Seeder
{

    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Events')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Events',
                    'varModuleName' => 'events',
                    'varTableName' => 'events',
                    'varModelName' => 'Events',
                    'varModuleClass' => 'EventsController',
                    'varModuleNameSpace' => 'Powerpanel\Events\\',
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
                    'varTitle' => 'Events',
                    'varModuleName' => 'events',
                    'varTableName' => 'events',
                    'varModelName' => 'Events',
                    'varModuleClass' => 'EventsController',
                    'varModuleNameSpace' => 'Powerpanel\Events\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Events')->first();
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

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Events'))->first();

        $pageModuleCodealias = DB::table('module')->select('id')->where('varTitle', 'Events')->first();
        $intFKModuleCodealias = $pageModuleCodealias->id;

        DB::table('events')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Event 1')), $intFKModuleCodealias),
            'intFKCategory' => '1',
            'varTitle' => 'Event 1',
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Event 1',
            'varMetaKeyword' => 'Event 1',
            'varMetaDescription' => 'Event 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('events')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Event 2')), $intFKModuleCodealias),
            'intFKCategory' => '2',
            'varTitle' => 'Event 2',
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Event 2',
            'varMetaKeyword' => 'Event 2',
            'varMetaDescription' => 'Event 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('events')->insert([
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Event 3')), $intFKModuleCodealias),
            'intFKCategory' => '3',
            'varTitle' => 'Event 3',
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Event 3',
            'varMetaKeyword' => 'Event 3',
            'varMetaDescription' => 'Event 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Events'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Events')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Events',
                    'varMetaKeyword' => 'Events',
                    'varMetaDescription' => 'Events',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Events'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Events')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Events',
                    'varMetaKeyword' => 'Events',
                    'varMetaDescription' => 'Events',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Events')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'events')->first();

        if (Schema::hasTable('visualcomposer')) {
            //Adding Events Module In visual composer
            $eventsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Events')->where('fkParentID', '0')->first();

            if (!isset($eventsModule->id) || empty($eventsModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Events',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $eventsModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Events')->where('fkParentID', '0')->first();

            $eventsChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Events')->where('fkParentID', '<>', '0')->first();

            if (!isset($eventsChild->id) || empty($eventsChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $eventsModule->id,
                    'varTitle' => 'Events',
                    'varIcon' => 'fa fa-calendar-check-o',
                    'varClass' => 'events',
                    'varTemplateName' => 'events::partial.events',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $latestEvents = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Events')->where('fkParentID', '0')->first();

            if (!isset($latestEvents->id) || empty($latestEvents->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $eventsModule->id,
                    'varTitle' => 'All Events',
                    'varIcon' => 'fa fa-calendar-check-o',
                    'varClass' => 'events-template',
                    'varTemplateName' => 'events::partial.all-events',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $currentMonthsEvents = DB::table('visualcomposer')->select('id')->where('varTitle', 'Current Months Events')->where('fkParentID', '0')->first();

            if (!isset($currentMonthsEvents->id) || empty($currentMonthsEvents->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $eventsModule->id,
                    'varTitle' => 'Current Months Events',
                    'varIcon' => 'fa fa-calendar-check-o',
                    'varClass' => 'events-template',
                    'varTemplateName' => 'events::partial.all-events',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
    }

}
