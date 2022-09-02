<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateTermsAndConditionsReadHistoryTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('terms_and_conditions_read_history', function (Blueprint $table) {
			$table->increments('id')->collation('utf8_general_ci');
			$table->unsignedInteger('fkIntUserId')->collation('utf8_general_ci')->nullable();
			$table->string('name',255)->collation('utf8_general_ci');
      $table->string('email')->collation('utf8_general_ci');
      $table->string('varIpAddress',50)->collation('utf8_general_ci');
			$table->char('chrTermsRead',1)->default('Y')->collation('utf8_general_ci');
			$table->char('chrDelete',1)->default('N')->collation('utf8_general_ci');
			$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
		});

		Schema::table('terms_and_conditions_read_history', function(Blueprint $table) {	
			$table->index('fkIntUserId');			
			$table->foreign('fkIntUserId')
			->references('id')
			->on('users')
			->onDelete('cascade');
		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('terms_and_conditions_read_history');
	}
}
