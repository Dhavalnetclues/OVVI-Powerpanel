<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class VideoGalleryTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Video Gallery')->first();
        
        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '3',
                    'varTitle' => 'Video Gallery',
                    'varModuleName' => 'video-gallery',
                    'varTableName' => 'video_gallery',
                    'varModelName' => 'VideoGallery',
                    'varModuleClass' => 'VideoGalleryController',
                    'varModuleNameSpace' => 'Powerpanel\VideoGallery\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            } else {
                DB::table('module')->insert([
                    'varTitle' => 'Video Gallery',
                    'varModuleName' => 'video-gallery',
                    'varTableName' => 'video_gallery',
                    'varModelName' => 'VideoGallery',
                    'varModuleClass' => 'VideoGalleryController',
                    'varModuleNameSpace' => 'Powerpanel\VideoGallery\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'Y',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'Video Gallery')->first();
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
                    'intFKModuleCode' => $pageModuleCode->id
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Video Gallery')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Video Gallery'))->first();
        
        DB::table('video_gallery')->insert([
            'varTitle' => 'Video Gallery 1',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Video Gallery 1')), $intFKModuleCode),
            'chrMain' => 'Y',
            'intDisplayOrder' => '1', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('video_gallery')->insert([
            'varTitle' => 'Video Gallery 2',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Video Gallery 2')), $intFKModuleCode),
            'chrMain' => 'Y',
            'intDisplayOrder' => '2', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
        DB::table('video_gallery')->insert([
            'varTitle' => 'Video Gallery 3',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Video Gallery 3')), $intFKModuleCode),
            'chrMain' => 'Y',
            'intDisplayOrder' => '3', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Video Gallery'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Video Gallery')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Video Gallery',
                    'varMetaKeyword' => 'Video Gallery',
                    'varMetaDescription' => 'Video Gallery',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Video Gallery'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Video Gallery')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Video Gallery',
                    'varMetaKeyword' => 'Video Gallery',
                    'varMetaDescription' => 'Video Gallery',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Video Gallery')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'video_gallery')->first();

         if (Schema::hasTable('visualcomposer')) {
             //Adding Video Gallery Module In visual composer
             $albumModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Video Gallery')->where('fkParentID', '0')->first();

             if (!isset($albumModule->id) || empty($albumModule->id)) {
                 DB::table('visualcomposer')->insert([
                     'fkParentID' => '0',
                     'varTitle' => 'Video Gallery',
                     'varIcon' => '',
                     'varClass' => '',
                     'varTemplateName' => '',
                     'varModuleID' => $pageModuleCode->id,
                     'created_at' => Carbon::now(),
                     'updated_at' => Carbon::now()
                 ]);
             }

             $albumModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Video Gallery')->where('fkParentID', '0')->first();

             $albumChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Video Gallery')->where('fkParentID', '<>', '0')->first();

             if (!isset($albumChild->id) || empty($albumChild->id)) {
                 DB::table('visualcomposer')->insert([
                     'fkParentID' => $albumModule->id,
                     'varTitle' => 'Video Gallery',
                     'varIcon' => 'fa fa-video-camera',
                     'varClass' => 'videogallery',
                     'varTemplateName' => 'video-gallery::partial.videogallery',
                     'varModuleID' => $pageModuleCode->id,
                     'created_at' => Carbon::now(),
                     'updated_at' => Carbon::now()
                 ]);
             }

             $latestphoto = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Video Gallery')->where('fkParentID', '0')->first();

             if (!isset($latestphoto->id) || empty($latestphoto->id)) {
                 DB::table('visualcomposer')->insert([
                     'fkParentID' => $albumModule->id,
                     'varTitle' => 'All Video Gallery',
                     'varIcon' => 'fa fa-video-camera',
                     'varClass' => 'videogallery-template',
                     'varTemplateName' => 'video-gallery::partial.all-videogallery',
                     'varModuleID' => $pageModuleCode->id,
                     'created_at' => Carbon::now(),
                     'updated_at' => Carbon::now()
                 ]);
             }
         }
    }

}
