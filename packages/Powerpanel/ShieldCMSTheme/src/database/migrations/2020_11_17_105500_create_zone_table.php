<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
class CreateZoneTable extends Migration {
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up() {
		Schema::create('zone', function (Blueprint $table) {
			$table->increments('zone_id')->collation('utf8_general_ci');
			$table->char('country_code',2)->collation('utf8_general_ci');
			$table->string('zone_name')->collation('utf8_general_ci');
			$table->char('chr_is_default_timezone',1)->default('N')->collation('utf8_general_ci');
		});
	}
	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::drop('zone');
	}
}
