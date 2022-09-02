<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateGridSettingTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('gridsetting', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('moduleid')->collation('utf8_general_ci')->nullable();
            $table->char('chrtab', 1)->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('columnno')->collation('utf8_general_ci')->nullable();
            $table->string('columnname', 255)->collation('utf8_general_ci')->nullable();
            $table->string('columnid', 255)->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('UserID')->collation('utf8_general_ci');
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
        Schema::drop('gridsetting');
    }

}
