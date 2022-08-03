<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFieldProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('uom', 'id_uom');
            $table->renameColumn('material_grup', 'id_material');
            $table->renameColumn('sub_material', 'id_sub_material');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('id_uom', 'uom');
            $table->renameColumn('id_material', 'material_grup');
            $table->renameColumn('id_sub_material', 'sub_material');
        });
    }
}
