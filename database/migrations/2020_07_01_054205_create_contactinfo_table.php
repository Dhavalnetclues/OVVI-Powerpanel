<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Carbon\Carbon;

class CreateContactInfoTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {

        Schema::create('contact_info', function (Blueprint $table) {

            $table->engine = 'InnoDB';
            $table->increments('id')->collation('utf8_general_ci');
            $table->string('varTitle', 255)->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varEmail')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varPhoneNo')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('varFax')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('txtDescription')->collation('utf8_general_ci')->nullable()->default(null);
            $table->unsignedInteger('intDisplayOrder')->collation('utf8_general_ci')->default(0);
            $table->unsignedInteger('fkIntImgId')->collation('utf8_general_ci')->nullable()->default(null);
            $table->text('txtAddress')->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('mailingaddress', 500)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('varLatitude', 500)->collation('utf8_general_ci')->nullable()->default('19.321187240779548');
            $table->string('varLongitude', 500)->collation('utf8_general_ci')->nullable()->default('-81.2274169921875');
            $table->string('varOpeningHours', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('chrLatitude', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->string('chrLongitude', 250)->collation('utf8_general_ci')->nullable()->default(null);
            $table->char('chrIsPrimary', 1)->collation('utf8_general_ci')->default('N');
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
        Schema::drop('contact_info');
    }

}
