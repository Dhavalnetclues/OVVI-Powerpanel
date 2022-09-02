<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateBannerTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('banner', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('fkMainRecord')->collation('utf8_general_ci')->default(0);
            $table->unsignedInteger('fkIntImgId')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varRotateTime', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('fkIntCoinImgId', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('fkIntVideoId')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('fkIntPageId')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('fkModuleId')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varVideoLink', 500)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varShortDescription', 500)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varVideoTitle', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrDisplayVideo', 1)->collation('utf8_general_ci')->default('N');
            $table->string('varTitle', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varSubTitle', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrDisplayLink', 1)->collation('utf8_general_ci')->default('N');
            $table->string('varLink', 500)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('fkIntVideoImgId', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varBannerType', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varBannerVersion', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci')->default(0);
            $table->text('txtDescription')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrMain', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrAddStar', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrApproved', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('intApprovedBy')->default(0);
            $table->char('chrIsPreview', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('UserID')->default(0);
            $table->char('chrRollBack', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrDefaultBanner', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrLetest', 1)->collation('utf8_general_ci')->default('N');
            $table->datetime('dtDateTime')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->datetime('dtEndDateTime')->nullable()->default(null);
            $table->unsignedInteger('intSearchRank')->default(2);
            $table->char('chrDraft', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrTrash', 1)->collation('utf8_general_ci')->default('N');
            $table->string('FavoriteID', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('LockUserID')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrLock', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrArchive', 1)->collation('utf8_general_ci')->default('N');
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
        Schema::drop('banner');
    }

}
