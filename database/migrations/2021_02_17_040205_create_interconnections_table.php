<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateInterconnectionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('interconnections', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('intParentCategoryId')->collation('utf8_general_ci')->default(0);
            $table->string('varTitle', 255)->collation('utf8_general_ci');
            $table->string('varSector',50)->collation('utf8mb4_unicode_ci')->nullable();
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci')->default(0);
            $table->string('txtShortDescription', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('fkIntDocId', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('fkMainRecord')->default(0);
            $table->unsignedInteger('intApprovedBy')->default(0);
            $table->unsignedInteger('UserID')->default(0);
            $table->char('chrMain', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrAddStar', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrApproved', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrRollBack', 1)->collation('utf8_general_ci')->default('N');
            $table->string('FavoriteID', 250)->collation('utf8mb4_unicode_ci')->nullable()->default(null);
            $table->char('chrTrash', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->char('chrPageActive', 2)->collation('utf8mb4_unicode_ci')->default('PU');
            $table->char('chrDraft', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->char('chrIsPreview',1)->default('N')->collation('utf8mb4_unicode_ci')->comment('Y=>Yes, N=>No');
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrLetest', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('intSearchRank')->default(1);
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
        Schema::drop('Interconnections');
    }

}
