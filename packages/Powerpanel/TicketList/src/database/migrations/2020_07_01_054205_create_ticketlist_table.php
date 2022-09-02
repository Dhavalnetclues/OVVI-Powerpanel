<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateTicketListTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('ticket_master', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('varTitle', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('intType')->collation('utf8_general_ci')->default(null);
            $table->text('varImage')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varCaptcher')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrStatus', 1)->collation('utf8_general_ci')->default('P');
            $table->text('txtShortDescription')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varLink', 500)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci')->default(null);
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrSubmitFlag', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('UserID')->collation('utf8_general_ci')->default(null);
            $table->text('EmailID')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('HoldMessage')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('OnGoingMessage')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('NewImplementationMessage')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('CompleteMessage')->collation('utf8_general_ci')->nullable()->default(null);
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
        Schema::drop('ticket_master');
    }

}
