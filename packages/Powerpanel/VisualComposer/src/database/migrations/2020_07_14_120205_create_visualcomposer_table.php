<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateVisualComposerTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('visualcomposer', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('fkParentID')->collation('utf8_general_ci');
            $table->string('varTitle', 255)->collation('utf8_general_ci');
            $table->string('varIcon', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varClass', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varTemplateName', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('varModuleID')->collation('utf8_general_ci')->nullable()->default(null);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('visualcomposer');
    }

}
