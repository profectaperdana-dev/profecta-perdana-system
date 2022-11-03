<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepreciations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('depreciations', function (Blueprint $table) {
            $table->id();
            $table->string('asset');
            $table->integer('amount');
            $table->integer('lifetime');
            $table->date('acquisition_year');
            $table->integer('acquisition_cost');
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
        Schema::dropIfExists('depreciations');
    }
}
