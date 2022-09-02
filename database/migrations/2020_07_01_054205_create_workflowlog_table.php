<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateWorkflowLogTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('workflow_log', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->char('charApproval', 1)->collation('utf8_general_ci')->default('N');
            $table->unsignedInteger('fkModuleId')->nullable();
            $table->unsignedInteger('fkRecordId')->nullable();
            $table->datetime('dtYes')->nullable()->default(null);
            $table->datetime('dtNo')->nullable()->default(null);
            $table->char('chrAfterSent', 1)->collation('utf8_general_ci')->default('N');
            $table->datetime('dtYesSent')->nullable()->default(null);
            $table->datetime('dtNoSent')->nullable()->default(null);
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
        Schema::drop('workflow_log');
    }

}
