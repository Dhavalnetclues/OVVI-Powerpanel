<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Carbon\Carbon;
class AliasTableSeeder extends Seeder {
  public function run() 
  {
  	DB::table('alias')->insert([
			'intFkModuleCode' =>  5,			
			'varAlias' => 'home'
		]);
	}
}