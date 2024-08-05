<?php

namespace App\Models\Cms;

use App\Models\EmployeeModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamModel extends Model
{
    use HasFactory;
    protected $connection = "cms_mysql";
    protected $table = "teams";

    public function employeeBy()
    {
        return $this->hasOne(EmployeeModel::class, 'id', 'employee_id');
    }
}
