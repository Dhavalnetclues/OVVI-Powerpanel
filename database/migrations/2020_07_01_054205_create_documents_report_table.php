<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateDocumentsReportTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('documents_report', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->unsignedInteger('intYear')->nullable()->default(null);
            $table->unsignedInteger('intMonth')->nullable()->default(null);
            $table->unsignedInteger('intMobileViewCount')->nullable()->default(null);
            $table->unsignedInteger('intMobileDownloadCount')->nullable()->default(null);
            $table->unsignedInteger('intDesktopViewCount')->nullable()->default(null);
            $table->unsignedInteger('intDesktopDownloadCount')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('documents_report');
    }

}
