<?php
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;

class CreateFolderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('folder', function (Blueprint $table) {
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('type')->collation('utf8_general_ci');
            $table->string('foldername')->collation('utf8_general_ci')->nullable();
            $table->timestamp('date')->default(DB::raw('CURRENT_TIMESTAMP'));
        });

    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('folder');
    }
}
