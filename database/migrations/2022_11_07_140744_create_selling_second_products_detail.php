<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingSecondProductsDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('second_sale_details', function (Blueprint $table) {
            $table->id();
            $table->integer('second_sale_id');
            $table->integer('product_second_id');
            $table->integer('qty');
            $table->integer('discount');
            $table->integer('discount_rp');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('second_sale_details');
    }
}
