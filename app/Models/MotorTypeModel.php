<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class MotorTypeModel extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'motor_types';

    public function brandBy()
    {
        return $this->belongsTo(MotorBrandModel::class, 'id_motor_brand', 'id')->withTrashed();
    }
}
