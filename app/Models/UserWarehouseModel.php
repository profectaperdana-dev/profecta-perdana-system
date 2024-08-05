<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserWarehouseModel extends Model
{
    use HasFactory;
    protected $table = 'user_warehouses';

    public function userBy()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function warehouseBy()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id');
    }
}
