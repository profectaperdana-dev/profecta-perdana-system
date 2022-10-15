<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TyreClaims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tyre_claims', function (Blueprint $table) {
            $table->id();
            $table->string('claim_number');
            $table->date('claim_date');
            $table->string('customer_id');
            $table->string('sub_name');
            $table->string('sub_phone');
            $table->string('email');
            $table->string('product_id');
            $table->integer('loan_product_id')->nullable();
            $table->string('material');
            $table->string('type_material');
            $table->string('car_brand_id');
            $table->string('car_type_id');
            $table->string('plate_number');
            $table->string('application');
            $table->string('dot');
            $table->string('serial_number');
            $table->string('rtd1');
            $table->string('rtd2');
            $table->string('rtd3');
            $table->string('complaint_area');
            $table->string('reason');
            $table->string('e_foto');
            $table->string('e_signature');
            $table->string('f_foto')->nullable();
            $table->string('f_signature')->nullable();
            $table->string('status')->default('0');
            $table->string('created_by');
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
        Schema::dropIfExists('tyre_claims');
    }
}
