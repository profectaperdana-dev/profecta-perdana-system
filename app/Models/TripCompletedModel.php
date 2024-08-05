<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCompletedModel extends Model
{
    use HasFactory;

    protected $table = 'trip_completeds';

    public function tripBy()
    {
        return $this->hasOne(TripModel::class, 'id', 'trip_id');
    }

    public function detailBy()
    {
        return $this->hasMany(TripCompletedDetailModel::class, 'completed_trip_id');
    }

    public function annotationBy()
    {
        return $this->hasMany(TripVehicleCompletedModel::class, 'id_trip');
    }

    public function gaBy()
    {
        return $this->hasOne(User::class, 'id', 'approval_ga')->withTrashed();
    }

    public function financeBy()
    {
        return $this->hasOne(User::class, 'id', 'approval_finance')->withTrashed();
    }
}
