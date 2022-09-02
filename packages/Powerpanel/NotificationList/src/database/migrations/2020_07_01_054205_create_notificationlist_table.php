<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateNotificationListTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('user_notifications', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $$table->unsignedInteger('fkIntModuleId');
            $table->unsignedInteger('fkIntUserId');
            $table->unsignedInteger('fkRecordId');
            $table->unsignedInteger('intOnlyForUserId')->nullable()->default(null);
            $table->text('txtNotification')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrNotificationType', 1)->collation('utf8_general_ci')->default('G');
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->string('varIpAddress', 255)->collation('utf8_general_ci')->nullable()->default(null);
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
        Schema::drop('user_notifications');
    }

}
