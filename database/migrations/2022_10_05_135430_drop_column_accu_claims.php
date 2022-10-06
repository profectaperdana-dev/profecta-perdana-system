<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropColumnAccuClaims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accu_claims', function (Blueprint $table) {
            $table->dropColumn('diagnosa');
            $table->dropColumn('phone_number');
            $table->dropColumn('sub_customer');
            $table->string('sub_name');
            $table->string('sub_phone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('accu_claims', function (Blueprint $table) {
            $table->string('diagnosa');
            $table->string('phone_number');
            $table->string('sub_customer');
            $table->dropColumn('sub_name');
            $table->dropColumn('sub_phone');
        });
    }
}
