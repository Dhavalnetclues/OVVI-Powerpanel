<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePollsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('polls', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8mb4_unicode_ci');
            $table->unsignedInteger('intAliasId');
            $table->unsignedInteger('fkMainRecord')->default(0);
            $table->string('varTitle', 255)->collation('utf8mb4_unicode_ci')->nullable()->default(null);
            $table->string('varShortDescription', 500)->collation('utf8mb4_unicode_ci')->nullable();
            $table->text('txtDescription')->collation('utf8mb4_unicode_ci');
            $table->char('chrMain', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->char('chrAddStar', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->unsignedInteger('intDisplayOrder')->collation('utf8mb4_unicode_ci')->default(0);
            $table->datetime('dtDateTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('dtEndDateTime')->nullable()->default(null);
            $table->char('chrPublish', 1)->collation('utf8mb4_unicode_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->char('chrApproved', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->unsignedInteger('intApprovedBy')->default(0);
            $table->char('chrRollBack', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->unsignedInteger('UserID')->default(0);
            $table->char('chrLetest', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->char('chrDraft', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->char('chrTrash', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->string('FavoriteID', 250)->collation('utf8mb4_unicode_ci')->nullable()->default(null);
            $table->char('chrIsPreview', 1)->default('N')->collation('utf8mb4_unicode_ci')->comment('Y=>Yes, N=>No');
            $table->unsignedInteger('LockUserID')->nullable()->default(null);
            $table->char('chrLock', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->datetime('dtApprovedDateTime')->nullable()->default(null);
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('polls');
    }

}
