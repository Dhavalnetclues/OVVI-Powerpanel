<?php

use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;

class CreateLoginHistoryTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('login_history', function (Blueprint $table) {
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('fkIntUserId')->collation('utf8_general_ci')->default(null);
            $table->string('varIpAddress', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varCountry_flag', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varCity', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varState_prov', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varCountry_name', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varBrowser_Name', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varBrowser_Version', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varBrowser_Platform', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varDevice', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrIsLoggedOut', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrActive', 1)->collation('utf8_general_ci')->default('Y');
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
        Schema::drop('login_history');
    }

}
