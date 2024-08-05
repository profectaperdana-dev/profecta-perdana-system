<?php

namespace App\Models;

use App\Models\Cms\TeamModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmployeeModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $connection = 'mysql';
    
    protected $table = 'employees';

    public function userBy()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    //vaation belogns to employee
    public function vacationBy()
    {
        return $this->hasMany(VacationModel::class, 'user_id');
    }
    
    public function teamBy()
    {
        return $this->belongsTo(TeamModel::class, 'id', 'employee_id');
    }
}
