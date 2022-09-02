<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateOrganizationsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('organizations', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('varTitle', 255)->collation('utf8_general_ci');
            $table->unsignedInteger('intParentCategoryId')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci')->default(0);
            $table->string('varDesignation', 255)->collation('utf8_general_ci')->default(null);
            $table->string('txtShortDescription', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('txtDescription')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('fkMainRecord')->default(0);
            $table->unsignedInteger('intApprovedBy')->default(0);
            $table->unsignedInteger('UserID')->default(0);
            $table->char('chrMain', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrAddStar', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrApproved', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrRollBack', 1)->collation('utf8_general_ci')->default('N');
            $table->text('varMetaTitle')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varMetaKeyword')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varMetaDescription')->collation('utf8_general_ci')->nullable()->default(null);
             $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrLetest', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('intSearchRank')->default(2);
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
        Schema::drop('organizations');
    }

}
