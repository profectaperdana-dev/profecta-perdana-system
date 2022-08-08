<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Products extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('kode_barang');
            $table->string('nama_barang');
            $table->string('no_seri');
            $table->string('uom');
            $table->string('material_grup');
            $table->string('sub_material');
            $table->float('berat');
            $table->float('harga_beli');
            $table->float('harga_jual');
            $table->float('harga_jual_nonretail');
            $table->integer('qty');
            $table->integer('minstok');
            $table->smallInteger('discontinue');
            $table->smallInteger('status');
            $table->string('foto_barang');
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
        Schema::dropIfExists('products');
    }
}
