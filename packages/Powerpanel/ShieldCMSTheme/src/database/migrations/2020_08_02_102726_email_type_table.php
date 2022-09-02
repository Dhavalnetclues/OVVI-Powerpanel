<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class EmailTypeTable extends Migration
{
	/**
	* Run the migrations.
	* @return void
	*/
	public function up(){
		Schema::create('email_type', function (Blueprint $table) {
			$table->engine = 'InnoDB';
			$table->increments('id')->collation('utf8_general_ci');
			$table->string('varEmailType')->collation('utf8_general_ci');
			$table->string('chrDelete')->collation('utf8_general_ci');
			$table->string('chrPublish')->collation('utf8_general_ci');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
$table->timestamp('updated_at')->default(NULL)->nullable();
		});
	}
	/**
	* Reverse the migrations.
	* @return void
	*/
	public function down(){
		Schema::drop('email_type');
	}
}
