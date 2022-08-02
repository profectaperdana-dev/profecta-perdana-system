<?php

namespace Database\Seeders;

use App\Models\UomModel;
use Illuminate\Database\Seeder;

class UomSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $model = new UomModel();
        $model->satuan = 'PCS';
        $model->created_by = '3';
        $model->save();
    }
}
