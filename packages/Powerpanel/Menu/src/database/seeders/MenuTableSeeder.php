<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use App\Http\Traits\slug;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class MenuTableSeeder extends Seeder
{

    public function run()
    {

        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Menu')->first();

        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'varTitle' => 'Menu',
                    'intFkGroupCode' => '0',
                    'varModuleName' => 'menu',
                    'varTableName' => 'menu',
                    'varModelName' => 'Menu',
                    'varModuleClass' => 'MenuController',
                    'varModuleNameSpace' => 'Powerpanel\NetquickTheme\\',
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'N',
                    'chrIsPowerpanel' => 'Y',
                    'chrIsGenerated' => 'N',
                    'decVersion' => 1.0,
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varPermissions' => 'list, create, edit, delete, publish',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            } else {
                DB::table('module')->insert([
                    'varTitle' => 'Menu',
                    'varModuleName' => 'menu',
                    'varTableName' => 'menu',
                    'varModelName' => 'Menu',
                    'varModuleClass' => 'MenuController',
                    'varModuleNameSpace' => 'Powerpanel\NetquickTheme\\',
                    'intDisplayOrder' => 0,
                    'chrIsFront' => 'N',
                    'chrIsPowerpanel' => 'Y',
                    'chrIsGenerated' => 'N',
                    'decVersion' => 1.0,
                    'chrPublish' => 'Y',
                    'chrDelete' => 'N',
                    'varPermissions' => 'list, create, edit, delete, publish',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]);
            }

            $pageModuleCode = DB::table('module')->where('varTitle', 'Menu')->first();
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

        $moduleCode = DB::table('module')->select('id')->where('varModuleName', 'menu')->first();

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Home')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 0,
            'intItemOrder' => 0,
            'intParentItemOrder' => 1,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Home'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Home',
            'txtPageUrl' => '/',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Services')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 0,
            'intItemOrder' => 0,
            'intParentItemOrder' => 2,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Services'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Services',
            'txtPageUrl' => 'services',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Services Detail')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 3,
            'intItemOrder' => 1,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Services Detail'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Services Detail',
            'txtPageUrl' => 'services-detail',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Service Category')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 3,
            'intItemOrder' => 2,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Service Category'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Service Category',
            'txtPageUrl' => 'service-category',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Team')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 0,
            'intItemOrder' => 0,
            'intParentItemOrder' => 4,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Team'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Team',
            'txtPageUrl' => 'team',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Team Detail')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 4,
            'intItemOrder' => 1,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Team Detail'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Team Detail',
            'txtPageUrl' => 'team-detail',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Testimonial')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 0,
            'intItemOrder' => 0,
            'intParentItemOrder' => 5,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Testimonial'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Testimonial',
            'txtPageUrl' => 'testimonial',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Contact Us')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 0,
            'intItemOrder' => 0,
            'intParentItemOrder' => 6,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Contact Us'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Contact Us',
            'txtPageUrl' => 'contact-us',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Pages')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 0,
            'intItemOrder' => 0,
            'intParentItemOrder' => 7,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Pages'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Pages',
            'txtPageUrl' => '#',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'About Us')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 1,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('About Us'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'About Us',
            'txtPageUrl' => 'about-us',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Products')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 2,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Products'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Products',
            'txtPageUrl' => 'products',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Product Detail')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 3,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Detail'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Product Detail',
            'txtPageUrl' => 'products-detail',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Product Category')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 4,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Product Category'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Product Category',
            'txtPageUrl' => 'product-category',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Photo Album')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 5,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Photo Album'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Photo Album',
            'txtPageUrl' => 'photo-album',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Video Album')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 6,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Video Album'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Video Album',
            'txtPageUrl' => 'video-album',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Video Gallery')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 7,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Video Gallery'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Video Gallery',
            'txtPageUrl' => 'video-gallery',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Privacy Policy')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 8,
            'intParentItemOrder' => 0,
            'intPosition' => 2,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Privacy Policy')[0], $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Privacy Policy',
            'txtPageUrl' => 'privacy-policy',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Terms  &amp; Conditions')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 9,
            'intParentItemOrder' => 0,
            'intPosition' => 2,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Terms  &amp; Conditions')[0], $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Terms  &amp; Conditions',
            'txtPageUrl' => 'terms-conditions',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Thank You')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 9,
            'intItemOrder' => 10,
            'intParentItemOrder' => 0,
            'intPosition' => 1,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Thank You'), $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Thank You',
            'txtPageUrl' => 'thank-you',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Privacy Policy')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 0,
            'intItemOrder' => 1,
            'intParentItemOrder' => 1,
            'intPosition' => 2,
            'intAliasId' => MyLibrary::insertAlias(slug::create_slug('Privacy Policy')[0], $moduleCode->id),
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Privacy Policy',
            'txtPageUrl' => 'privacy-policy',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        $pageObj = DB::table('cms_page')->select('id')->where('varTitle', 'Sitemap')->first();

        DB::table('menu')->insert([
            'intParentMenuId' => 0,
            'intItemOrder' => 1,
            'intParentItemOrder' => 1,
            'intPosition' => 2,
            'intAliasId' => null,
            'intPageId' => (isset($pageObj->id) ? $pageObj->id : null),
            'varTitle' => 'Sitemap',
            'txtPageUrl' => 'sitemap',
            'chrActive' => 'Y',
            'chrMegaMenu' => 'N',
            'chrPublish' => 'Y',
            'chrDelete' => 'N',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }

}
