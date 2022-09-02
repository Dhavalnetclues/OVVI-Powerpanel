<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateJobsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->bigIncrements('id')->collation('utf8_general_ci');
            $table->string('queue')->collation('utf8_general_ci');
            $table->longText('payload')->collation('utf8_general_ci');
            $table->tinyInteger('attempts')->collation('utf8_general_ci')->unsigned();
            $table->tinyInteger('reserved')->collation('utf8_general_ci')->unsigned();
            $table->unsignedInteger('reserved_at')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('available_at')->collation('utf8_general_ci');
            $table->unsignedInteger('created_at')->collation('utf8_general_ci');
            $table->index(['queue', 'reserved', 'reserved_at'])->collation('utf8_general_ci');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jobs');
    }
}
