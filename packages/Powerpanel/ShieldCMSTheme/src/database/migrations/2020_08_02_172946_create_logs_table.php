<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateLogsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('log', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('fkIntUserId')->collation('utf8_general_ci');
            $table->unsignedInteger('fkIntModuleId')->collation('utf8_general_ci');
            $table->integer('intRecordId')->collation('utf8_general_ci');
            $table->string('varTitle')->collation('utf8_general_ci')->nullable();
            $table->text('txtOldVal')->collation('utf8_general_ci')->nullable();
            $table->text('txtNewVal')->collation('utf8_general_ci')->nullable();
            $table->char('chrPublish')->collation('utf8_general_ci');
            $table->string('chrDelete')->collation('utf8_general_ci');
            $table->string('varIpAddress')->collation('utf8_general_ci');
            $table->string('varAction')->collation('utf8_general_ci')->nullable();
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(NULL)->nullable();
        });

        Schema::table('log', function(Blueprint $table) {
            $table->index('fkIntUserId');
            $table->index('fkIntModuleId');

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
        Schema::drop('log');
    }

}
