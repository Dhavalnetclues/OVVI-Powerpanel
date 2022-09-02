<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
class CreateTimezoneTable extends Migration {
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up() {
		Schema::create('timezone', function (Blueprint $table) {
			$table->integer('zone_id')->collation('utf8_general_ci');
			$table->string('abbreviation')->collation('utf8_general_ci');
			$table->decimal('time_start', 11, 0)->collation('utf8_general_ci');	
			$table->integer('gmt_offset')->collation('utf8_general_ci');
			$table->char('dst',1)->collation('utf8_general_ci');
		});
	}
	/**
  * Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::drop('timezone');
	}
}
