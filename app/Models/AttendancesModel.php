<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendancesModel extends Model
{
    use HasFactory;
    protected $connection = 'tracking_mysql';
    protected $table = 'attendances';

    public function userBy()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function employeeBy()
    {
        return $this->hasOne(EmployeeModel::class, 'created_by', 'employee_id');
    }
}
