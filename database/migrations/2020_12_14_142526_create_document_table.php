<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDocumentTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('documents', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('fkIntUserId')->collation('utf8_general_ci');
            $table->unsignedInteger('fk_folder')->collation('utf8_general_ci');
            $table->string('varfolder', 250)->collation('utf8_general_ci');
            $table->text('txtDocumentName')->collation('utf8_general_ci');
            $table->unsignedInteger('intMobileViewCount')->collation('utf8_general_ci');
            $table->unsignedInteger('intDesktopViewCount')->collation('utf8_general_ci');
            $table->unsignedInteger('intMobileDownloadCount')->collation('utf8_general_ci');
            $table->unsignedInteger('intDesktopDownloadCount')->collation('utf8_general_ci');
            $table->text('txtSrcDocumentName')->collation('utf8_general_ci');
            $table->string('varDocumentExtension', 10)->collation('utf8_general_ci');
            $table->char('chrIsUserUploaded', 1)->default('N')->collation('utf8_general_ci');
            $table->char('chrPublish', 1)->default('Y')->collation('utf8_general_ci');
            $table->char('chrDelete', 1)->default('N')->collation('utf8_general_ci');
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(NULL)->nullable();
        });

        Schema::table('documents', function(Blueprint $table) {
            $table->index('fkIntUserId');
            $table->foreign('fkIntUserId')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('documents');
    }

}
