<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateLicenceRegisterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('licence_register', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('intAliasId');            
            $table->unsignedInteger('intAliasId')->collation('utf8_general_ci');
            $table->string('varSector',250)->collation('utf8_general_ci');
            $table->string('varTitle',255)->collation('utf8_general_ci');
            $table->string('varTagLine',255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->Integer('varCompanyId')->collation('utf8_general_ci');
            $table->string('varContactPerson',255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varWeblink1')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varWeblink2')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varWeblink3')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varContactAddress')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('txtDescription')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varEmail',100)->nullable()->collation('utf8_general_ci')->default(null);
            $table->string('varStatus',250)->collation('utf8_general_ci');
            $table->string('varService',250)->collation('utf8_general_ci');
            $table->text('dtDateTime')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varIssuenote',250)->collation('utf8_general_ci');
            $table->char('chrRenewal', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->text('dtRenewaldate')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varRenewalNote',250)->collation('utf8_general_ci');
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci');
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->char('chrApproved', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->unsignedInteger('intApprovedBy')->default(0);
            $table->char('chrRollBack', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->unsignedInteger('UserID')->default(0);
            $table->char('chrLetest', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->unsignedInteger('intSearchRank')->default(2);
            $table->char('chrDraft', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->char('chrPageActive', 2)->collation('utf8mb4_unicode_ci')->default('PU');
            $table->string('varPassword', 100)->collation('utf8mb4_unicode_ci')->default('N');
            $table->char('chrTrash', 1)->collation('utf8mb4_unicode_ci')->default('N');
            $table->string('FavoriteID', 250)->collation('utf8mb4_unicode_ci')->nullable()->default(null);
            $table->char('chrIsPreview',1)->default('N')->collation('utf8mb4_unicode_ci')->comment('Y=>Yes, N=>No');
            $table->text('varMetaTitle')->collation('utf8mb4_unicode_ci');
            $table->text('varMetaKeyword')->collation('utf8mb4_unicode_ci');
            $table->text('varMetaDescription')->collation('utf8mb4_unicode_ci');
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
        Schema::drop('licence_register');
    }
}
