<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateCommentsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('Comments', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('Fk_ParentCommentId')->default('0');
            $table->unsignedInteger('intRecordID')->default('0');
            $table->unsignedInteger('fkMainRecord')->default('0');
            $table->string('varModuleNameSpace', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varNameSpace', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varCmsPageComments', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('UserID')->default('0');
            $table->unsignedInteger('intCommentBy')->default('0');
            $table->string('varModuleTitle', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
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
        Schema::drop('Comments');
    }

}
