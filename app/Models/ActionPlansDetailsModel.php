<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPlansDetailsModel extends Model
{
    use HasFactory;
    protected $connection = 'tracking_mysql';
    protected $table = 'plans_details';

    public function PlanResults()
    {
        return $this->hasMany(ActionPlansResultsModel::class, 'plan_detail_id', 'id');
    }
}
