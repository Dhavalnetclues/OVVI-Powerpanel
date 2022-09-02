<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateFormBuilderLeadTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('formbuilder_lead', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('fk_formbuilder_id')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('formdata')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('filename')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varIpAddress', 20)->collation('utf8_general_ci')->nullable()->default(null);
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
        Schema::drop('formbuilder_lead');
    }

}
