<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateWorkflowTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('workflow', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('varType', 100)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varTitle', 255)->collation('utf8_general_ci');
            $table->string('varActivity', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varAction', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('varAfter')->nullable()->default(null);
            $table->text('txtAfter')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('varFrequancyPositive')->nullable()->default(null);
            $table->text('txtFrequancyPositive')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('varFrequancyNegative')->nullable()->default(null);
            $table->text('txtFrequancyNegative')->collation('utf8_general_ci')->nullable();
            $table->text('varUserId')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('intModuleId')->nullable()->default(null);
            $table->string('varUserRoles', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('intCategoryId')->nullable()->default(null);
            $table->char('charNeedApproval', 1)->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrNeedAddPermission', 1)->collation('utf8_general_ci')->default('N');
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
        Schema::drop('workflow');
    }

}
