<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseModel extends Model
{
    use HasFactory;
    protected $table = 'warehouses';
    protected $guarded = ['id'];
}
