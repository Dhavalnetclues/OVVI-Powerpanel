<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateVideoGalleryTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('video_gallery', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('fkMainRecord')->collation('utf8_general_ci')->default(0);
            $table->string('varTitle', 255)->collation('utf8_general_ci');
            $table->text('txtLink')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('fkIntImgId', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('intAliasId')->collation('utf8_general_ci');
            $table->char('chrMain', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrAddStar', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci');
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrApproved', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('intApprovedBy')->default(0);
            $table->char('chrRollBack', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('UserID')->default(0);
            $table->char('chrLetest', 1)->collation('utf8_general_ci')->default('N');
            $table->datetime('dtDateTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('dtEndDateTime')->nullable()->default(null);
            $table->unsignedInteger('intSearchRank')->default(2);
            $table->char('chrDraft', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrTrash', 1)->collation('utf8_general_ci')->default('N');
            $table->string('FavoriteID', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('LockUserID')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrLock', 1)->collation('utf8_general_ci')->default('N');
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
        Schema::drop('video_gallery');
    }

}
