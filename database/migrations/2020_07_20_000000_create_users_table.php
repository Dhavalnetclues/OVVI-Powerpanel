<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateUsersTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('users', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('name', 255)->collation('utf8_general_ci');
            $table->string('email')->collation('utf8_general_ci')->unique();
            $table->string('personalId')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('fkIntImgId')->collation('utf8_general_ci')->nullable();
            $table->string('password')->collation('utf8_general_ci');
            $table->string('pass_change_dt')->collation('utf8_general_ci');
            $table->unsignedInteger('intAttempts')->collation('utf8_general_ci')->nullable();
            $table->timestamp('First_Attempts_Time')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->timestamp('Last_Attempts_Time')->default(DB::raw('CURRENT_TIMESTAMP'))->nullable();
            $table->char('chrAuthentication', 1)->default('N')->collation('utf8_general_ci');
            $table->unsignedInteger('Int_Authentication_Otp')->collation('utf8_general_ci')->nullable();
            $table->char('chrDelete', 1)->default('N')->collation('utf8_general_ci');
            $table->char('chrPublish', 1)->default('Y')->collation('utf8_general_ci');
            $table->char('chrSecurityQuestions', 1)->default('N')->collation('utf8_general_ci');
            $table->string('SecurityQuestions_start_date')->collation('utf8_general_ci');
            $table->unsignedInteger('intSearchRank')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('varQuestion1')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('varQuestion2')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('varQuestion3')->collation('utf8_general_ci')->nullable();
            $table->string('varAnswer1')->collation('utf8_general_ci')->nullable();
            $table->string('varAnswer2')->collation('utf8_general_ci')->nullable();
            $table->string('varAnswer3')->collation('utf8_general_ci')->nullable();
            $table->string('varAnswer4')->collation('utf8_general_ci')->nullable();
            $table->rememberToken()->collation('utf8_general_ci');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(NULL)->nullable();
        });

        // Schema::table('users', function(Blueprint $table) 
        // {
        //   $table->index('fkIntImgId');          
        //   $table->foreign('fkIntImgId')
        //     ->references('id')
        //     ->on('image');
        // });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('users');
    }

}
