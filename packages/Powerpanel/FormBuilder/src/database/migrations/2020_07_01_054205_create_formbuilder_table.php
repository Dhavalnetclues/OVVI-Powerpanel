<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateFormBuilderTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('form_builder', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('varName', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('FormTitle', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('Description')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varFormDescription')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('fkIntImgId')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varEmail', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varAdminSubject', 500)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varAdminContent', 500)->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrCheckUser', 1)->collation('utf8_general_ci')->default('P');
            $table->string('varUserSubject', 500)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varUserContent', 500)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varThankYouMsg')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('UserID')->collation('utf8_general_ci')->default(null);
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
        Schema::drop('form_builder');
    }

}
