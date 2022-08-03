<?php

namespace Database\Seeders;

use App\Models\SubMaterialModel;
use Illuminate\Database\Seeder;

class SubMaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new SubMaterialModel();
        $model->nama_sub_material = 'Tyre';
        $model->created_by = '3';
        $model->save();
    }
}
