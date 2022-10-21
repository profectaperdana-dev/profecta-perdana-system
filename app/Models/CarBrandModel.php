<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarBrandModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'car_brands';

    public function typeBy()
    {
        return $this->hasMany(CarTypeModel::class, 'id_car_brand')->withTrashed();
    }
}
