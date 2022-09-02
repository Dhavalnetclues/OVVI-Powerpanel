<?php
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;

class EntrustSetupTables extends Migration
{
		/**
		 * Run the migrations.
		 *
		 * @return  void
		 */
		public function up()
		{
				// Create table for storing roles
				Schema::create('roles', function (Blueprint $table) {
						$table->increments('id')->collation('utf8_general_ci');
						$table->string('name')->collation('utf8_general_ci')->unique();
						$table->string('display_name')->collation('utf8_general_ci')->nullable();
						$table->string('description')->collation('utf8_general_ci')->nullable();
						$table->char('chr_publish')->default('Y')->collation('utf8_general_ci')->comment('Y=>Yes, N=>No');
						$table->char('chr_delete')->default('N')->collation('utf8_general_ci')->comment('Y=>Yes, N=>No');
						$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
$table->timestamp('updated_at')->default(NULL)->nullable();
				});

				// Create table for associating roles to users (Many-to-Many)
				Schema::create('role_user', function (Blueprint $table) {
						$table->integer('user_id')->collation('utf8_general_ci')->unsigned();
						$table->integer('role_id')->collation('utf8_general_ci')->unsigned();

						$table->foreign('user_id')->collation('utf8_general_ci')->references('id')->on('users')
								->onUpdate('cascade')->collation('utf8_general_ci')->onDelete('cascade');
						$table->foreign('role_id')->collation('utf8_general_ci')->references('id')->on('roles')
								->onUpdate('cascade')->collation('utf8_general_ci')->onDelete('cascade');

						$table->primary(['user_id', 'role_id'])->collation('utf8_general_ci');
				});

				// Create table for storing permissions
				Schema::create('permissions', function (Blueprint $table) {
						$table->engine = 'InnoDB';
						$table->increments('id')->collation('utf8_general_ci');
						$table->unsignedInteger('intFKModuleCode')
						->nullable()
						->index()
						->references('id')
						->on('module');
						$table->string('name')->collation('utf8_general_ci')->unique();
						$table->string('display_name')->collation('utf8_general_ci')->nullable();
						$table->string('description')->collation('utf8_general_ci')->nullable();
						$table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
$table->timestamp('updated_at')->default(NULL)->nullable();
				});

				// Create table for associating permissions to roles (Many-to-Many)
				Schema::create('permission_role', function (Blueprint $table) {
						$table->integer('permission_id')->collation('utf8_general_ci')->unsigned();
						$table->integer('role_id')->collation('utf8_general_ci')->unsigned();

						$table->foreign('permission_id')->collation('utf8_general_ci')->references('id')->on('permissions')
								->onUpdate('cascade')->collation('utf8_general_ci')->onDelete('cascade');
						$table->foreign('role_id')->collation('utf8_general_ci')->references('id')->on('roles')
								->onUpdate('cascade')->collation('utf8_general_ci')->onDelete('cascade');

						$table->primary(['permission_id', 'role_id'])->collation('utf8_general_ci');
				});
		}

		/**
		 * Reverse the migrations.
		 *
		 * @return  void
		 */
		public function down()
		{
				Schema::drop('permission_role');
				Schema::drop('permissions');
				Schema::drop('role_user');
				Schema::drop('roles');
		}
}
