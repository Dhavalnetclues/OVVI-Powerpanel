<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreatePayonlineTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('payonline', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('txnId', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('name', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('companyName', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('email', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('phone', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('invoiceNo', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('amount', 100)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('currency', 3)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('cardType', 10)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('note')->collation('utf8_general_ci')->nullable()->default(null);
            $table->date('payment_date')->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrPublish', 1)->collation('utf8_general_ci')->default('Y');
            $table->char('chrDelete', 1)->collation('utf8_general_ci')->default('N');
            $table->string('varIpAddress', 20)->collation('utf8_general_ci')->nullable()->default(null);
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
        Schema::drop('payonline');
    }

}
