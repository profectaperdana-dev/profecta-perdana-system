<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeAddressFieldsAtCustomers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('village');
            $table->string('district');
            $table->string('city');
            $table->string('province');
            $table->integer('due_date');
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
            $table->dropColumn('village');
            $table->dropColumn('district');
            $table->dropColumn('city');
            $table->dropColumn('province');
            $table->dropColumn('due_date');
        });
    }
}
