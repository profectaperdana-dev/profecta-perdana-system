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
        for ($i = 0; $i < 1000; $i++) {
            $model = new UomModel();
            $model->satuan = 'PCS' . $i;
            $model->created_by = '3';
            $model->save();
        }
    }
}
