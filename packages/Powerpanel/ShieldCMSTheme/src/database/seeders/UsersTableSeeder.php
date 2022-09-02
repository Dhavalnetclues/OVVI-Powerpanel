<?php
namespace Database\Seeders;

use App\Helpers\MyLibrary;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $pageModuleCode = DB::table('module')->select('id')->where('varTitle', 'Users')->first();
        if (!isset($pageModuleCode->id) || empty($pageModuleCode->id)) {
            if (\Schema::hasColumn('module', 'intFkGroupCode')) {
                DB::table('module')->insert([
                    'varTitle' => 'Users',
                    'intFkGroupCode' => '2',
                    'varModuleName' => 'users',
                    'varTableName' => 'users',
                    'varModelName' => 'User',
                    'varModuleClass' => 'UserController',
                    'varModuleNameSpace' => 'Powerpanel\ShieldCMSTheme\\',
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
                    'varTitle' => 'Users',
                    'varModuleName' => 'users',
                    'varTableName' => 'users',
                    'varModelName' => 'User',
                    'varModuleClass' => 'UserController',
                    'varModuleNameSpace' => 'Powerpanel\ShieldCMSTheme\\',
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

            $pageModuleCode = DB::table('module')->where('varTitle', 'Users')->first();
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

        $users = [
            [
                'name' => 'Super Admin',
                'email' => MyLibrary::getEncryptedString('netquick@netclues.net', true),
                'password' => bcrypt('Admin@123'),
                'personalId' => MyLibrary::getEncryptedString('testbynetclues@gmail.com', true),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'Admin',
                'email' => MyLibrary::getEncryptedString('ppadmin@netclues.com', true),
                'password' => bcrypt('Admin@123'),
                'personalId' => MyLibrary::getEncryptedString('testbynetclues@gmail.com', true),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'User',
                'email' => MyLibrary::getEncryptedString('testbynetclues@gmail.com', true),
                'password' => bcrypt('Admin@123'),
                'personalId' => MyLibrary::getEncryptedString('testbynetclues@gmail.com', true),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        foreach ($users as $key => $value) {
            DB::table('users')->insert($value);
        }
    }

}
