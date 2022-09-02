<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateLiveUserTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('live_user', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
              $table->string('varIpAddress', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varContinent_code', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varContinent_name', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCountry_code2', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCountry_code3', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCountry_name', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCountry_capital', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varState_prov', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varDistrict', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCity', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varZipcode', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varLatitude', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varLongitude', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varIs_eu', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCalling_code', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCountry_tld', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varLanguages', 100)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCountry_flag', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varGeoname_id', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varIsp', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varConnection_type', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varOrganization', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCurrencyCode', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCurrencyName', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varCurrencySymbol', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varTime_zoneName', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varTime_zoneOffset', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varTime_zoneCurrent_time', 100)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varTime_zoneCurrent_time_unix', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varTime_zoneIs_dst', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->string('varTime_zoneDst_savings', 50)->collation('utf8_general_ci')->nullable()->default(null);
              $table->text('txtBrowserInf')->collation('utf8_general_ci')->nullable()->default(null);
              $table->char('ChrBlock', 1)->collation('utf8_general_ci')->default('N');
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
        Schema::drop('live_user');
    }

}
