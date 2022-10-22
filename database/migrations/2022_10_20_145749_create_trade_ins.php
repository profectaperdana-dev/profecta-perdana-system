<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTradeIns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trade_ins', function (Blueprint $table) {
            $table->id();
            $table->string('trade_in_number');
            $table->string('trade_in_date');
            $table->integer('createdBy');
            $table->string('customer');
            $table->string('customer_phone');
            $table->string('customer_email');
            $table->string('customer_nik');
            $table->integer('total');
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
        Schema::dropIfExists('trade_ins');
    }
}
