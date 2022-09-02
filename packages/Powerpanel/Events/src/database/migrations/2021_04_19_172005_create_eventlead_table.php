<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateEventLeadTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('event_lead', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('eventId');
            $table->datetime('startDate')->nullable()->default(null);
            $table->datetime('endDate')->nullable()->default(null);
            $table->string('startTime', 255)->nullable()->default(null);
            $table->string('endTime', 255)->nullable()->default(null);
            $table->unsignedInteger('noOfAttendee')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('attendeeDetail')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('message')->collation('utf8_general_ci')->nullable()->default(null);
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
        Schema::drop('event_lead');
    }

}
