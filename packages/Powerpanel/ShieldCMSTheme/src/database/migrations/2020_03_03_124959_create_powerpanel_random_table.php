<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreatePowerpanelRandomTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('powerpanel_random', function (Blueprint $table) {
            $table->bigIncrements('id')->collation('utf8_general_ci');
            $table->unsignedInteger('fkIntUserId')->collation('utf8_general_ci')->nullable();
            $table->string('name', 255)->collation('utf8_general_ci');
            $table->string('email', 255)->collation('utf8_general_ci');
            $table->unsignedInteger('intCode')->collation('utf8_general_ci')->nullable();
            $table->char('chrExpiry', 1)->default('N')->collation('utf8_general_ci');
            $table->char('chrDelete', 1)->default('N')->collation('utf8_general_ci');
            $table->string('varIpAddress', 255)->collation('utf8_general_ci')->nullable();
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
        Schema::drop('powerpanel_random');
    }

}
