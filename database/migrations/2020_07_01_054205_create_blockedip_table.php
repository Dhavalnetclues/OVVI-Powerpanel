<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateBlockedIPTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('blocked_ips', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->text('varEmail')->collation('utf8_general_ci')->nullable()->default(null);
             $table->string('varIpAddress', 20)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('txtBrowserInf')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varCountry_name', 50)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varCountry_flag', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varUrl', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varNewUrl', 255)->collation('utf8_general_ci')->nullable()->default(null);
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
        Schema::drop('blocked_ips');
    }

}
