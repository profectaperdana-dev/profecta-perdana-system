<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActionPlansModel extends Model
{
    use HasFactory;
    protected $connection = 'tracking_mysql';
    protected $table = 'plans';

    public function userBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    public function employeeBy()
    {
        return $this->hasOne(EmployeeModel::class, 'created_by', 'employee_id');
    }

    public function PlanDetails()
    {
        return $this->hasMany(ActionPlansDetailsModel::class, 'plan_id', 'id');
    }
}
