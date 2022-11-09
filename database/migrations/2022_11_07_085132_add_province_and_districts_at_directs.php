<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProvinceAndDistrictsAtDirects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('direct_sales', function (Blueprint $table) {
            $table->string('province')->after('cust_email');
            $table->string('sub_district')->after('district');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('direct_sales', function (Blueprint $table) {
            $table->dropColumn('province');
            $table->dropColumn('sub_district');
        });
    }
}
