<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateMessagingsystemTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('messagingsystem', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('FromID', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('FromEmail', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('ToID')->collation('utf8_general_ci');
            $table->string('ToEmail', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varShortDescription')->collation('utf8mb4_unicode_ci')->nullable()->default(null);
            $table->string('fkIntDocId', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('fkIntImgId', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('FromName', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('ToName', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('varread', 1)->collation('utf8_general_ci')->nullable()->default('N');
            $table->char('varEdit', 1)->collation('utf8_general_ci')->nullable()->default('N');
            $table->char('varQuote', 1)->collation('utf8_general_ci')->nullable()->default('N');
            $table->unsignedInteger('varQuoteId')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('varDeleted', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('fkMainRecord')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varTitle', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrMain', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrAddStar', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci')->default(0);
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrApproved', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('intApprovedBy')->default(0);
            $table->char('chrRollBack', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('UserID')->default(0);
            $table->char('chrLetest', 1)->collation('utf8_general_ci')->default('N');
            $table->datetime('dtDateTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('dtEndDateTime')->nullable()->default(null);
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
        Schema::drop('messagingsystem');
    }

}
