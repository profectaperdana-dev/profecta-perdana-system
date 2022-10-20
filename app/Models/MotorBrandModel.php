<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorBrandModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'motor_brands';
    public function typeBy()
    {
        return $this->hasMany(MotorTypeModel::class, 'id_motor_brand')->withTrashed();
    }
}
