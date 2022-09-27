<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClaims extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claims', function (Blueprint $table) {
            $table->id();
            // kanan atas
            $table->string('claim_number');
            $table->date('claim_date');
            $table->string('customer_id');
            // kiri atas
            $table->integer('product_id');
            $table->string('car_type');
            $table->string('plate_number');

            // cek awal
            $table->string('e_voltage');
            $table->string('e_cca');
            $table->string('e_starting');
            $table->string('e_charging');
            $table->string('diagnosa');
            $table->string('e_submittedBy');
            $table->string('e_receivedBy');

            // cek akhir
            $table->string('f_voltage')->nullable();
            $table->string('f_cca')->nullable();
            $table->string('f_starting')->nullable();
            $table->string('f_charging')->nullable();
            $table->string('result')->nullable();
            $table->string('f_submittedBy')->nullable();
            $table->string('f_receivedBy')->nullable();

            // keterangan
            $table->integer('status')->default(0);
            $table->string('cost')->nullable();
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
        Schema::dropIfExists('claims');
    }
}
