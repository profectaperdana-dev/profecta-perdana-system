<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WarehouseModel extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $table = 'warehouses';
    protected $guarded = ['id'];

    public function customerBy()
    {
        return $this->hasMany(CustomerModel::class, 'area_cust_id', 'id_area');
    }
}
