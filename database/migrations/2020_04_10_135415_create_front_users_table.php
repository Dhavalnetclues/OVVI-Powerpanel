<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateFrontUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('front_users', function (Blueprint $table) {
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('name')->collation('utf8_general_ci');
            $table->string('email')->collation('utf8_general_ci')->unique();
            $table->string('fk_image_id',11)->collation('utf8_general_ci');
            $table->string('timezone')->collation('utf8_general_ci')->nullable();
            $table->string('password')->collation('utf8_general_ci');
            $table->char('chr_delete',1)->default('N')->collation('utf8_general_ci');
            $table->char('chr_publish',1)->default('Y')->collation('utf8_general_ci');
            $table->rememberToken()->collation('utf8_general_ci');
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
        Schema::drop('front_users');
    }
}
