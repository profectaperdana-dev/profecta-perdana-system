<?php

namespace Database\Seeders;

use App\Models\MaterialModel;
use Illuminate\Database\Seeder;

class MaterialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new MaterialModel();
        $model->nama_material = 'Continental';
        $model->created_by = '3';
        $model->save();
    }
}
