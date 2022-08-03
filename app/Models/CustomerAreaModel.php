<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerAreaModel extends Model
{
    use HasFactory;
    protected $table = "customer_areas";
    protected $guarded = ["id"];
}
