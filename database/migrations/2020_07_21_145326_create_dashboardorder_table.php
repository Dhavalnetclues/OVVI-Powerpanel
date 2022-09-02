<?php
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;
use Illuminate\Database\Schema\Blueprint;

class CreateDashboardOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dashboardorder', function (Blueprint $table) {
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('intDisplayOrder')->collation('utf8_general_ci')->nullable();
            $table->unsignedInteger('UserID')->collation('utf8_general_ci')->default(null);
            $table->text('txtWidgetSetting')->collation('utf8_general_ci')->nullable()->default(null);
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
        Schema::drop('dashboardorder');
    }
}
