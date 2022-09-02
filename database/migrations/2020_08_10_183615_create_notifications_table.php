<?php
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
class CreateNotificationsTable extends Migration {
	/**
	* Run the migrations.
	* @return void
	*/
	public function up() {
		Schema::create('notifications', function (Blueprint $table){
				$table->engine = 'InnoDB';
				$table->increments('id')->collation('utf8_general_ci');
        $table->unsignedInteger('fkIntModuleId')->collation('utf8_general_ci');
        $table->unsignedInteger('fkIntUserId')->collation('utf8_general_ci');
        $table->unsignedInteger('fkIntRecordCode')->collation('utf8_general_ci');
        $table->text('txtNotification')->collation('utf8_general_ci')->nullable();
        $table->text('txtRecentNotification')->collation('utf8_general_ci')->nullable();
        $table->char('chrPublish',1)->default('Y')->collation('utf8_general_ci');
        $table->char('chrDelete',1)->default('N')->collation('utf8_general_ci');
        $table->char('chrRead',1)->default('N')->collation('utf8_general_ci');
        $table->char('chrRecordDelete',1)->default('N')->collation('utf8_general_ci');
        $table->string('varIpAddress')->collation('utf8_general_ci');
        $table->timestamp('created_at')->collation('utf8_general_ci')->default(Carbon::now());
        $table->timestamp('updated_at')->default(NULL)->nullable();
			});

		Schema::table('notifications', function(Blueprint $table) {
			$table->index('fkIntModuleId');
			$table->index('fkIntUserId');

			$table->foreign('fkIntUserId')
			->references('id')
			->on('users')
			->onDelete('cascade');

			$table->foreign('fkIntModuleId')
			->references('id')
			->on('module')
			->onDelete('cascade');
		});

	}
	/**
	* Reverse the migrations.
	*
	* @return void
	*/
	public function down() {
		Schema::drop('notifications');
	}
}
