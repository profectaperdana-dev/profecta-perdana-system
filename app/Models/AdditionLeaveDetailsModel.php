<?php

namespace App\Models;

use App\Models\Cms\TeamModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdditionLeaveDetailsModel extends Model
{
    use HasFactory;

    protected $connection = 'mysql';

    protected $table = 'leave_addition_details';

    public function userBy()
    {
        return $this->hasOne(User::class, 'id', 'user_id')->withTrashed();
    }

    public function createdBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by')->withTrashed();
    }

    public function teamBy()
    {
        return $this->belongsTo(EmployeeModel::class, 'employee_id', 'id');
    }
}
