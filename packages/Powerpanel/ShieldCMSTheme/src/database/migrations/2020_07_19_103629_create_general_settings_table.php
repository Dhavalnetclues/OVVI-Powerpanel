<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
class CreateGeneralSettingsTable extends Migration {
	/**
	* Run the migrations.
	*
	* @return void
	*/
	public function up() {
		Schema::create('general_setting', function (Blueprint $table) 
		{
			$table->increments('id')->collation('utf8_general_ci');
			$table->string('fieldName',50)->nullable()->collation('utf8_general_ci');
			$table->text('fieldValue')->nullable()->collation('utf8_general_ci');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
			$table->timestamp('updated_at')->default(NULL)->nullable();
		});
	}
	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::drop('general_setting');
	}
}
