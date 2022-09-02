<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->collation('utf8_general_ci')->unique();
            $table->integer('user_id')->collation('utf8_general_ci')->nullable();
            $table->string('ip_address', 45)->nullable()->collation('utf8_general_ci');
            $table->text('user_agent')->collation('utf8_general_ci')->nullable();
            $table->text('payload')->collation('utf8_general_ci');
            $table->integer('last_activity')->collation('utf8_general_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sessions');
    }
}
