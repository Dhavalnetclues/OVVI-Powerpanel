<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class StaticBlocksTableSeeder extends Seeder
{
    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Staticblocks')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '0',
                    'varTitle' => 'Staticblocks',
                    'varModuleName' => 'static-block',
                    'varTableName' => 'static_block',
                    'varModelName' => 'StaticBlocks',
                    'varModuleClass' => 'StaticBlocksController',
                    'varModuleNameSpace' => 'Powerpanel\StaticBlocks\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'N',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('module')->insert([
                    'varTitle' => 'Staticblocks',
                    'varModuleName' => 'static-block',
                    'varTableName' => 'static_block',
                    'varModelName' => 'StaticBlocks',
                    'varModuleClass' => 'StaticBlocksController',
                    'varModuleNameSpace' => 'Powerpanel\StaticBlocks\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'N',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'Staticblocks')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Staticblocks')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        // $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Static Block'))->first();

        // if (!isset($exists->id)) {
        //     if (\Schema::hasColumn('cms_page', 'chrMain')) {
        //         DB::table('cms_page')->insert([
        //             'varTitle' => htmlspecialchars_decode('Static Block'),
        //             'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Static Block')), $cmsModuleCode->id),
        //             'intFKModuleCode' => $intFKModuleCode,
        //             'txtDescription' => '',
        //             'chrMain' => 'Y',
        //             'chrPublish' => 'Y',
        //             'chrDelete' => 'N',
        //             'varMetaTitle' => 'Static Block',
        //             'varMetaKeyword' => 'Static Block',
        //             'varMetaDescription' => 'Static Block',
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]);
        //     } else {
        //         DB::table('cms_page')->insert([
        //             'varTitle' => htmlspecialchars_decode('Static Block'),
        //             'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Static Block')), $cmsModuleCode->id),
        //             'intFKModuleCode' => $intFKModuleCode,
        //             'txtDescription' => '',
        //             'chrPublish' => 'Y',
        //             'chrDelete' => 'N',
        //             'varMetaTitle' => 'Static Block',
        //             'varMetaKeyword' => 'Static Block',
        //             'varMetaDescription' => 'Static Block',
        //             'created_at' => Carbon::now(),
        //             'updated_at' => Carbon::now(),
        //         ]);
        //     }
        // }

        $shortCode = slug::create_slug('section_01');
        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'static_block')->first();
        DB::table('static_block')->insert([
            'varTitle' => 'section_01',
            'varShortCode' => $shortCode,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('section_01'), $moduleCode->id),
            'txtDescription' => "",
            'varExternalLink' => "",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $recId = DB::table('static_block')->select('id')->where('varShortCode', $shortCode)->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_01_01')->first();
        DB::table('static_block')->insert([
            'varTitle' => "Welcome to Company",
            'varShortCode' => slug::create_slug('Welcome to Company'),
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Welcome to Company'), $moduleCode->id),
            'fkIntImgId' => (isset($fkIntImgId->id) ? $fkIntImgId->id : null),
            'txtDescription' => "

					&lt;p&gt;Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.&lt;/p&gt;
					&lt;blockquote&gt;
						&lt;p&gt;Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry&#039;s standard dummy text ever since the 1500s.&lt;/p&gt;
					&lt;/blockquote&gt;
					&lt;p&gt;It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.&lt;/p&gt;

			",
            'varExternalLink' => "#",
            'intChildMenu' => (isset($recId->id) ? $recId->id : null),
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $shortCode = slug::create_slug('section_15');
        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'static_block')->first();
        DB::table('static_block')->insert([
            'varTitle' => 'section_15',
            'varShortCode' => $shortCode,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('section_15'), $moduleCode->id),
            'txtDescription' => "",
            'varExternalLink' => "",
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $recId = DB::table('static_block')->select('id')->where('varShortCode', $shortCode)->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_15_01')->first();
        DB::table('static_block')->insert([
            'varTitle' => "Dishes",
            'varShortCode' => slug::create_slug('Dishes'),
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Dishes'), $moduleCode->id),
            'fkIntImgId' => (isset($fkIntImgId->id) ? $fkIntImgId->id : null),
            'txtDescription' => "503",
            'varExternalLink' => "#",
            'intChildMenu' => (isset($recId->id) ? $recId->id : null),
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $recId = DB::table('static_block')->select('id')->where('varShortCode', $shortCode)->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_15_02')->first();
        DB::table('static_block')->insert([
            'varTitle' => "Customers",
            'varShortCode' => slug::create_slug('Customers'),
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Customers'), $moduleCode->id),
            'fkIntImgId' => (isset($fkIntImgId->id) ? $fkIntImgId->id : null),
            'txtDescription' => "2389",
            'varExternalLink' => "#",
            'intChildMenu' => (isset($recId->id) ? $recId->id : null),
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $recId = DB::table('static_block')->select('id')->where('varShortCode', $shortCode)->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_15_03')->first();
        DB::table('static_block')->insert([
            'varTitle' => "Awards",
            'varShortCode' => slug::create_slug('Awards'),
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Awards'), $moduleCode->id),
            'fkIntImgId' => (isset($fkIntImgId->id) ? $fkIntImgId->id : null),
            'txtDescription' => "20",
            'varExternalLink' => "#",
            'intChildMenu' => (isset($recId->id) ? $recId->id : null),
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $recId = DB::table('static_block')->select('id')->where('varShortCode', $shortCode)->first();

        $fkIntImgId = DB::table('image')->select('id')->where('txtImageName', 'theme_01_section_15_04')->first();
        DB::table('static_block')->insert([
            'varTitle' => "Private Event",
            'varShortCode' => slug::create_slug('Private Event'),
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Private Event'), $moduleCode->id),
            'fkIntImgId' => (isset($fkIntImgId->id) ? $fkIntImgId->id : null),
            'txtDescription' => "2010",
            'varExternalLink' => "#",
            'intChildMenu' => (isset($recId->id) ? $recId->id : null),
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}
