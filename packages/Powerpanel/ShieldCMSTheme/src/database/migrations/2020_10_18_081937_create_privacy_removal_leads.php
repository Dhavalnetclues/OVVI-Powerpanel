<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrivacyRemovalLeads extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('privacy_removal_leads', function (Blueprint $table) {
            
            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->text('varName',400)->collation('utf8_general_ci');
            $table->text('varEmail',400)->collation('utf8_general_ci'); 
            $table->text('txtReason')->collation('utf8_general_ci')->nullable();
            $table->char('chrIsAuthorized', 1)->default('N')->collation('utf8_general_ci');
            $table->char('chrIsEmailVerified', 1)->default('N')->collation('utf8_general_ci');
            $table->string('varIpAddress', 50)->collation('utf8_general_ci');
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
        Schema::drop('site_monitor');
    }
}
