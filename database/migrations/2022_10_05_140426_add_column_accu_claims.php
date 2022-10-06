<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnAccuClaims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('accu_claims', function (Blueprint $table) {
            $table->string('e_foto')->nullable();
            $table->string('f_foto')->nullable();
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
            $table->dropColumn('e_foto');
            $table->dropColumn('f_foto');
        });
    }
}
