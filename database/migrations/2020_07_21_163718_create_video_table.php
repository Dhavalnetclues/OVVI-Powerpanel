<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateVideoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('video', function (Blueprint $table) 
        {
            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('fkIntUserId')->collation('utf8_general_ci');
            $table->string('varVideoName',255)->nullable()->collation('utf8_general_ci');
            $table->text('txtVideoOriginalName')->collation('utf8_general_ci');
            $table->string('varVideoExtension',5)->nullable()->collation('utf8_general_ci');
            $table->char('chrIsUserUploaded',1)->default('Y')->collation('utf8_general_ci');
             $table->string('varMediaVideoUrlType',255)->nullable()->collation('utf8_general_ci');
             $table->text('varExtVideoUrl')->collation('utf8_general_ci');
            $table->string('youtubeId',100)->nullable()->collation('utf8_general_ci');
            $table->string('vimeoId',100)->nullable()->collation('utf8_general_ci');
            $table->char('chrPublish',1)->default('Y')->collation('utf8_general_ci');
            $table->char('chrDelete',1)->default('N')->collation('utf8_general_ci');
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
        Schema::drop('video');
    }
}
