<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateComplaintLeadTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('career_lead', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('intAliasId');            
            $table->unsignedInteger('fkIntServiceId')->collation('utf8_general_ci')->default(null);
            $table->string('varTitle', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varEmail')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varService', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varCompany', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->date('complaint_date')->collation('utf8_general_ci')->nullable()->default();
            $table->text('varPhoneNo')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('txtUserMessage')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('company_response')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('attachment')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
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
        Schema::drop('career_lead');
    }

}
