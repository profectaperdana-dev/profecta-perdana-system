<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('kode_cust')->unique();
            $table->string('nama_cust');
            $table->string('alamat_cust');
            $table->string('no_telepon_cust');
            $table->string('email_cust')->unique();
            $table->integer('category_cust_id');
            $table->integer('area_cust_id');
            $table->string('coordinate');
            $table->integer('credit_limit');
            $table->boolean('status');
            $table->string('reference_image');
            $table->integer('created_by');
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
        Schema::dropIfExists('customers');
    }
}
