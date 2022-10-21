<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarTypeModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'car_types';

    public function brandBy()
    {
        return $this->belongsTo(CarBrandModel::class, 'id_car_brand', 'id');
    }
}
