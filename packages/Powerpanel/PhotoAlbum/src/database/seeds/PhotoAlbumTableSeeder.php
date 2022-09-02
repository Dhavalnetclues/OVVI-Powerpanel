<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class PhotoAlbumTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Photo Album')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Photo Album',
                    'varModuleName' => 'photo-album',
                    'varTableName' => 'photo_album',
                    'varModelName' => 'PhotoAlbum',
                    'varModuleClass' => 'PhotoAlbumController',
                    'varModuleNameSpace' => 'Powerpanel\PhotoAlbum\\',
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
                    'varTitle' => 'Photo Album',
                    'varModuleName' => 'photo-album',
                    'varTableName' => 'photo_album',
                    'varModelName' => 'PhotoAlbum',
                    'varModuleClass' => 'PhotoAlbumController',
                    'varModuleNameSpace' => 'Powerpanel\PhotoAlbum\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Photo Album')->first();
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

        $pageModuleCode = DB::table('module')->where('varTitle', 'Photo Album')->first();
        $cmsModuleCode = DB::table('module')->where('varTitle', 'pages')->first();
        $intFKModuleCode = $pageModuleCode->id;

        $exists = DB::table('cms_page')->select('id')->where('varTitle', htmlspecialchars_decode('Photo Album'))->first();
        
         DB::table('photo_album')->insert([
            'varTitle' => 'Photo Album 1',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Photo Album 1')), $intFKModuleCode),
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Photo Album 1',
            'varMetaKeyword' => 'Photo Album 1',
            'varMetaDescription' => 'Photo Album 1',
            'chrMain' => 'Y',
            'intDisplayOrder' => '1', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
         DB::table('photo_album')->insert([
            'varTitle' => 'Photo Album 2',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Photo Album 2')), $intFKModuleCode),
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Photo Album 2',
            'varMetaKeyword' => 'Photo Album 2',
            'varMetaDescription' => 'Photo Album 2',
            'chrMain' => 'Y',
            'intDisplayOrder' => '2', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
         DB::table('photo_album')->insert([
            'varTitle' => 'Photo Album 3',
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Photo Album 3')), $intFKModuleCode),
            'varShortDescription' => 'The standard Lorem Ipsum passage, used since the 1500s',
            'txtDescription' => '',
            'varMetaTitle' => 'Photo Album 3',
            'varMetaKeyword' => 'Photo Album 3',
            'varMetaDescription' => 'Photo Album 3',
            'chrMain' => 'Y',
            'intDisplayOrder' => '3', 
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);

        if (!isset($exists->id)) {
            if (\Schema::hasColumn('cms_page', 'chrMain')) {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Photo Album'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Photo Album')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrMain' => 'Y',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Photo Album',
                    'varMetaKeyword' => 'Photo Album',
                    'varMetaDescription' => 'Photo Album',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('cms_page')->insert([
                    'varTitle' => htmlspecialchars_decode('Photo Album'),
                    'intAliasId' => MyLibrary::insertAlias(slug::create_slug(htmlspecialchars_decode('Photo Album')), $cmsModuleCode->id),
                    'intFKModuleCode' => $intFKModuleCode,
                    'txtDescription' => '',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varMetaTitle' => 'Photo Album',
                    'varMetaKeyword' => 'Photo Album',
                    'varMetaDescription' => 'Photo Album',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Photo Album')->first();

        $moduleCode = DB::table('module')->select('id')->where('varTableName', 'photo_album')->first();

        if (Schema::hasTable('visualcomposer')) {
            //Adding Photo Album Module In visual composer
            $albumModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Photo Album')->where('fkParentID', '0')->first();

            if (!isset($albumModule->id) || empty($albumModule->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => '0',
                    'varTitle' => 'Photo Album',
                    'varIcon' => '',
                    'varClass' => '',
                    'varTemplateName' => '',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $albumModule = DB::table('visualcomposer')->select('id')->where('varTitle', 'Photo Album')->where('fkParentID', '0')->first();

            $albumChild = DB::table('visualcomposer')->select('id')->where('varTitle', 'Photo Album')->where('fkParentID', '<>', '0')->first();

            if (!isset($albumChild->id) || empty($albumChild->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $albumModule->id,
                    'varTitle' => 'Photo Album',
                    'varIcon' => 'fa fa-camera-retro',
                    'varClass' => 'photoalbum',
                    'varTemplateName' => 'photo-album::partial.photoalbum',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $latestphoto = DB::table('visualcomposer')->select('id')->where('varTitle', 'All Photo Album')->where('fkParentID', '0')->first();

            if (!isset($latestphoto->id) || empty($latestphoto->id)) {
                DB::table('visualcomposer')->insert([
                    'fkParentID' => $albumModule->id,
                    'varTitle' => 'All Photo Album',
                    'varIcon' => 'fa fa-camera-retro',
                    'varClass' => 'photoalbum-template',
                    'varTemplateName' => 'photo-album::partial.all-photoalbum',
                    'varModuleID' => $pageModuleCode->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }
        }
    }

}
