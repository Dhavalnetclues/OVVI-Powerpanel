<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreatePasswordResetsTable extends Migration
{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
				Schema::create('password_resets', function (Blueprint $table) {
						$table->string('email')->collation('utf8_general_ci')->index();
						$table->string('token')->collation('utf8_general_ci')->index();
						$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
						$table->timestamp('updated_at')->default(NULL)->nullable();
				});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return void
		 */
		public function down()
		{
				Schema::drop('password_resets');
		}
}
