<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TripCompletedDetailModel extends Model
{
    use HasFactory;

    protected $table = 'trip_completed_details';

    public function completedBy()
    {
        return $this->belongsTo(TripCompletedModel::class, 'completed_trip_id', 'id');
    }
}
