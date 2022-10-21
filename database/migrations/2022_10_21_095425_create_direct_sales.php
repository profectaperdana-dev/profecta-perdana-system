<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDirectSales extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('direct_sales', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->date('order_date');
            $table->string('cust_name');
            $table->string('cust_phone');
            $table->string('cust_ktp')->nullable();
            $table->string('cust_email')->nullable();
            $table->string('plate_number');
            $table->integer('car_brand_id')->nullable();
            $table->integer('car_type_id')->nullable();
            $table->integer('motor_brand_id')->nullable();
            $table->integer('motor_type_id')->nullable();
            $table->text('remark');
            $table->integer('total_excl');
            $table->integer('total_ppn');
            $table->integer('total_incl');
            $table->boolean('isPaid');
            $table->integer('created_by');
            $table->string('pdf_do');
            $table->string('pdf_invoice');
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
        Schema::dropIfExists('direct_sales');
    }
}
