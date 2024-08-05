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
    
    protected $connection = 'mysql';

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

    public function userAuthBy()
    {
        return $this->hasMany(UserAuthorizationModel::class, 'user_id');
    }

    public function userWarehouseBy()
    {
        return $this->hasMany(UserWarehouseModel::class, 'user_id');
    }

    public function checkAuth($auth_id)
    {
        $selected_auth = UserAuthorizationModel::where('user_id', $this->id)->where('auth_id', $auth_id)->first();
        if ($selected_auth != null) {
            return true;
        } else return false;
    }

    public function getMasterSection()
    {
        $master_section = [];
        $selected_user_auth = UserAuthorizationModel::where('user_id', $this->id)->latest('created_at')->get();
        foreach ($selected_user_auth as $value) {
            
                array_push($master_section, $value->authBy->master_section);
            
        }
        $unique = array_unique($master_section);
        sort($unique);
        return $unique;
    }

    public function getSection()
    {
        $id = [];
        $section = [];
        $selected_user_auth = UserAuthorizationModel::where('user_id', $this->id)->latest('created_at')->get();
        foreach ($selected_user_auth as $value) {
            array_push($id, $value->auth_id);
        }
        $auth = AuthorizationModel::whereIn('id', $id)->select('master_section', 'section')->distinct()->get();

        return $auth;
    }
}
