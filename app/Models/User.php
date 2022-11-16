<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;
    use HasRoles;
    use SoftDeletes;

    protected $table = 'users';
    protected $guarded = ['id'];

    public function roleBy()
    {
        return $this->hasOne(RoleModel::class, 'id', 'role_id')->withTrashed();
    }
    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id')->withTrashed();
    }
    public function jobBy()
    {
        return $this->hasOne(JobModel::class, 'id', 'job_id')->withTrashed();
    }

    public function employeeBy()
    {
        return $this->hasOne(EmployeeModel::class, 'id', 'employee_id')->withTrashed();
    }
}
