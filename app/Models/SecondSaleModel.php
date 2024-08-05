<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SecondSaleModel extends Model
{
    use HasFactory;

    protected $table = 'second_sales';

    public function second_sale_details()
    {
        return $this->hasMany(SecondSaleDetailModel::class, 'second_sale_id');
    }
    public function secondSaleBy()
    {
        return $this->hasOne(User::class, 'id', 'created_by');
    }

    // warehouse
    public function warehouse()
    {
        return $this->hasOne(WarehouseModel::class, 'id', 'warehouse_id');
    }
}
