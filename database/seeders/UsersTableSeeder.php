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
