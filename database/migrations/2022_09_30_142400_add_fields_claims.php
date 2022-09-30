<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldsClaims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->string('car_brand_id')->nullable();
            $table->string('material')->nullable();
            $table->string('type_material')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn('car_brand_id');
            $table->dropColumn('material');
            $table->dropColumn('type_material');
        });
    }
}
