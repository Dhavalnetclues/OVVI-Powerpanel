<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateFeedbackLeadTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('getdemo_leads', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('varName', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varEmail')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varPhoneNo')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrSatisfied', 1)->collation('utf8_general_ci')->default('Y');
            $table->string('varVisitfor', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varGoal', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('chrCategory')->collation('utf8_general_ci')->default(null);
            $table->text('txtUserMessage')->collation('utf8_general_ci')->nullable()->default(null);
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
        Schema::drop('getdemo_leads');
    }

}
