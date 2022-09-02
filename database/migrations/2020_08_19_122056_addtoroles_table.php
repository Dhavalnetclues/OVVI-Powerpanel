<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToRolesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->char('chrIsAdmin', 1)->collation('utf8_general_ci')->default('N')->after('chr_delete');
            $table->char('chrApprovalRole', 1)->collation('utf8_general_ci')->default('N')->after('chrIsAdmin');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('roles', function (Blueprint $table) {
            $table->dropColumn('chrIsAdmin');
            $table->dropColumn('chrApprovalRole');
        });
    }
}
