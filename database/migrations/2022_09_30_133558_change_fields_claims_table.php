<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldsClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('claims', function (Blueprint $table) {
            $table->dropColumn(['customer_id', 'product_id', 'car_type']);
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
            $table->integer('customer_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->string('car_type')->nullable();
        });
    }
}
