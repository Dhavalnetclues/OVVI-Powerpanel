<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateSiteMonitorTable extends Migration
{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
				Schema::create('site_monitor', function (Blueprint $table) {
						$table->engine = 'InnoDB';
						$table->tinyIncrements('id')->collation('utf8_general_ci');
						$table->string('varTitle',255)->collation('utf8_general_ci');
						$table->enum('chrDelete',  ['Y', 'N'])->default('N')->collation('utf8_general_ci');						
				});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
			 Schema::drop('site_monitor');
		}
}
