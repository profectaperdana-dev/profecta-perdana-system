<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->date('order_date');
            $table->integer('customers_id');
            $table->boolean('ppn');
            $table->string('remark');
            $table->integer('created_by');
            $table->integer('top')->nullable();
            $table->integer('payment')->nullable();
            $table->enum('payment_type', ['Cash', 'Transfer']);
            $table->integer('order_total');
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
        Schema::dropIfExists('sales_orders');
    }
}
