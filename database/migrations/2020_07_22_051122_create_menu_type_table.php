<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateMenuTypeTable extends Migration
{
		/**
		 * Run the migrations.
		 *
		 * @return void
		 */
		public function up()
		{
			Schema::create('menu_type', function (Blueprint $table) {
				$table->engine = 'InnoDB';
				$table->increments('id')->collation('utf8_general_ci');
				$table->string('varTitle', 255)->collation('utf8_general_ci');
				$table->unsignedInteger('intAliasId')->collation('utf8_general_ci')->nullable();
				$table->char('chrPublish',1)->default('Y')->collation('utf8_general_ci');           
				$table->char('chrDelete',1)->default('N')->collation('utf8_general_ci');
				$table->timestamp('created_at')->collation('utf8_general_ci')->default(Carbon::now());
				$table->timestamp('updated_at')->default(NULL)->nullable();
			});

			Schema::table('menu_type', function(Blueprint $table){	
				$table->index('intAliasId');
				$table->foreign('intAliasId')
				->references('id')
				->on('alias')
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
				Schema::drop('menu_type');
		}
}
