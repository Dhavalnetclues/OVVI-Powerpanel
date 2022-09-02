<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreatePublicationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('publications', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('varTitle', 255)->collation('utf8_general_ci');
            $table->unsignedInteger('intAliasId')->collation('utf8_general_ci');
            $table->datetime('dtDateTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('dtEndDateTime')->nullable()->default(null);
            $table->string('fkIntDocId', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('fkIntImgId', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('txtDescription')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varShortDescription')->collation('utf8_general_ci');
            $table->unsignedInteger('txtCategories')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci');
            $table->unsignedInteger('fkMainRecord')->collation('utf8_general_ci')->default(0);
            $table->unsignedInteger('intApprovedBy')->default(0);
            $table->unsignedInteger('UserID')->default(0);
            $table->char('chrMain', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrAddStar', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrApproved', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrRollBack', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrLetest', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('intSearchRank')->default(2);
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrIsPreview', 1)->collation('utf8_general_ci')->default('N');
            $table->text('varMetaTitle')->collation('utf8_general_ci');
            $table->text('varMetaKeyword')->collation('utf8_general_ci');
            $table->text('varMetaDescription')->collation('utf8_general_ci');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrDraft', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrPageActive', 2)->collation('utf8mb4_unicode_ci')->default('PU');
            $table->string('varPassword', 100)->collation('utf8mb4_unicode_ci')->default('N');
            $table->char('chrTrash', 1)->collation('utf8_general_ci')->default('N');
            $table->string('FavoriteID', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('LockUserID')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrLock', 1)->collation('utf8_general_ci')->default('N');
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
    public function down() {
        Schema::drop('publications');
    }

}
