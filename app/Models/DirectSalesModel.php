<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DirectSalesModel extends Model
{
    use HasFactory;
    protected $table = 'direct_sales';

    public function directSalesDetailBy()
    {
        return $this->hasMany(DirectSalesDetailModel::class, 'direct_id');
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function carBrandBy()
    {
        return $this->hasOne(CarBrandModel::class, 'id', 'car_brand_id')->withTrashed();
    }

    public function carTypeBy()
    {
        return $this->hasOne(CarTypeModel::class, 'id', 'car_type_id')->withTrashed();
    }

    public function motorBrandBy()
    {
        return $this->hasOne(MotorBrandModel::class, 'id', 'motor_brand_id')->withTrashed();
    }

    public function motorTypeBy()
    {
        return $this->hasOne(MotorTypeModel::class, 'id', 'motor_type_id')->withTrashed();
    }
}
