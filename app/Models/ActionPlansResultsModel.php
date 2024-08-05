<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionPlansResultsModel extends Model
{
    use HasFactory;
    protected $connection = 'tracking_mysql';
    protected $table = 'plans_result';
}
