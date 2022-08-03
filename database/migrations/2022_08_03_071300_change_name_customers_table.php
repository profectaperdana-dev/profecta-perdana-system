<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeNameCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('kode_cust', 'code_cust');
            $table->renameColumn('nama_cust', 'name_cust');
            $table->renameColumn('alamat_cust', 'address_cust');
            $table->renameColumn('no_telepon_cust', 'phone_cust');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->renameColumn('code_cust', 'kode_cust');
            $table->renameColumn('name_cust', 'nama_cust');
            $table->renameColumn('address_cust', 'alamat_cust');
            $table->renameColumn('phone_cust', 'no_telepon_cust');
        });
    }
}
