<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripModel extends Model
{
    use HasFactory;

    protected $table = 'trips';

    public function completedBy()
    {
        return $this->belongsTo(TripCompletedModel::class, 'id', 'trip_id');
    }

    // relasi dengan employee
    public function employeeBy()
    {
        return $this->hasOne(EmployeeModel::class, 'id', 'id_employee')->withTrashed();
    }

    public function getName()
    {
        $getname = explode(',', $this->id_employee);
        $arr_name = '';
        foreach ($getname as $value) {
            $getemployee = EmployeeModel::where('id', $value)->first();
            $arr_name .=  $getemployee->name . ', ';
        }

        return rtrim($arr_name, ', ');
    }

    public function routeBy()
    {
        return $this->hasMany(TripRouteModel::class, 'id_trip');
    }

    public function getNik()
    {
        $getname = explode(',', $this->id_employee);
        $arr_name = '';
        foreach ($getname as $value) {
            $getemployee = EmployeeModel::where('id', $value)->first();
            $arr_name .=  $getemployee->nik . ', ';
        }

        return rtrim($arr_name, ', ');
    }

    public function vehicleBy()
    {
        return $this->hasMany(TripVehicleModel::class, 'id_trip');
    }
}
