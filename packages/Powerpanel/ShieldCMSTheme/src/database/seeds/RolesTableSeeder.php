<?php
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name' => 'netquick_admin',
                'display_name' => 'NetQuick Admin - Super Admin',
                'description' => 'Full Permission',
                'chrIsAdmin' => 'Y',
                'chrApprovalRole' => 'N',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'netclues_admin',
                'display_name' => 'Netclues Admin',
                'description' => 'Partial Permission',
                'chrIsAdmin' => 'Y',
                'chrApprovalRole' => 'N',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'name' => 'client_roles',
                'display_name' => 'Client Account',
                'description' => 'Limited Permission',
                'chrIsAdmin' => 'Y',
                'chrApprovalRole' => 'N',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];
        foreach ($roles as $key => $value) {
            DB::table('roles')->insert($value);
        }
    }
}
