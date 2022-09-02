<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;

class AliasTableSeeder extends Seeder
{
    public function run()
    {
        DB::table('alias')->insert([
            'intFkModuleCode' => 5,
            'varAlias' => 'home',
        ]);
    }
}
