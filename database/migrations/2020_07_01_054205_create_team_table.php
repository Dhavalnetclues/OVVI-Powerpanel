<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateTeamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('intAliasId')->collation('utf8_general_ci');
            $table->string('varTitle',255)->collation('utf8_general_ci');
            $table->string('varTagLine',255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varDepartment',255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('fkIntImgId')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('txtDescription')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varEmail',100)->nullable()->collation('utf8_general_ci')->default(null);
            $table->string('varPhoneNo')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('textAddress')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('txtSocialLinks')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci');
            $table->char('chrDelete')->collation('utf8_general_ci')->default('N');
            $table->char('chrPublish')->collation('utf8_general_ci')->default('Y');
            $table->text('varMetaTitle')->collation('utf8_general_ci');
            $table->text('varMetaKeyword')->collation('utf8_general_ci');
            $table->text('varMetaDescription')->collation('utf8_general_ci');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(NULL)->nullable();
        });
    }
  /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('team');
    }
}
