<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReturnPurchases extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('return_purchases', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_order_id');
            $table->string('return_number');
            $table->date('return_date');
            $table->integer('total');
            $table->string('return_reason');
            $table->integer('created_by');
            $table->string('pdf_return')->nullable();
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
        Schema::dropIfExists('return_purchases');
    }
}
