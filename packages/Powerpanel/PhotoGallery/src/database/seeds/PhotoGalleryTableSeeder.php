<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Helpers\MyLibrary;
use App\Http\Traits\slug;

class PhotoGalleryTableSeeder extends Seeder {

    public function run() {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Photo Gallery')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'intFkGroupCode' => '2',
                    'varTitle' => 'Photo Gallery',
                    'varModuleName' => 'photo-gallery',
                    'varTableName' => 'photo_gallery',
                    'varModelName' => 'PhotoGallery',
                    'varModuleClass' => 'PhotoGalleryController',
                    'varModuleNameSpace' => 'Powerpanel\PhotoGallery\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'N',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            } else {
                DB::table('module')->insert([
                    'varTitle' => 'Photo Gallery',
                    'varModuleName' => 'photo-gallery',
                    'varTableName' => 'photo_gallery',
                    'varModelName' => 'PhotoGallery',
                    'varModuleClass' => 'PhotoGalleryController',
                    'varModuleNameSpace' => 'Powerpanel\PhotoGallery\\',
                    'decVersion' => 1.0,
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'N',
                    'chrIsPowerpanel' => 'Y',
                    'varPermissions' => 'list, create, edit, delete, publish,reviewchanges',
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now()
                ]);
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'Photo Gallery')->first();
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
            
            
            DB::table('photo_gallery')->insert([
                'varTitle' => 'Photo Gallery 1',
                'intPhotoAlbumId' => '1',
                'txtDescription' => '',
                'chrMain' => 'Y',
                'intDisplayOrder' => '1',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            DB::table('photo_gallery')->insert([
                'varTitle' => 'Photo Gallery 2',
                'intPhotoAlbumId' => '2',
                'txtDescription' => '',
                'chrMain' => 'Y',
                'intDisplayOrder' => '2',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
            DB::table('photo_gallery')->insert([
                'varTitle' => 'Photo Gallery 3',
                'intPhotoAlbumId' => '3',
                'txtDescription' => '',
                'chrMain' => 'Y',
                'intDisplayOrder' => '3',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now()
            ]);
        }
    }

}
