<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateVideoModuleRelTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('video_module_rel', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('intFkVideoId')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('intFkModuleCode')->nullable()->collation('utf8_general_ci');
            $table->unsignedInteger('intRecordId')->nullable()->collation('utf8_general_ci');
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
        Schema::drop('video_module_rel');
    }

}
